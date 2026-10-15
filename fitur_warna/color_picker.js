(() => {
    const container = document.querySelector(".container[data-csrf-token]");
    const picker = document.getElementById("pilihWarna");
    const preview = document.getElementById("previewWarna");
    const hexLabel = document.getElementById("kodeWarna");
    const message = document.getElementById("pesan");
    const historyList = document.getElementById("riwayatWarna");
    const libraryList = document.getElementById("libraryWarna");
    const historyCount = document.getElementById("jumlahHistory");
    const libraryCount = document.getElementById("jumlahLibrary");
    const maxHistory = 25;
    const csrfToken = container.dataset.csrfToken;
    let history = [];
    let library = [];

    function normalizeHex(value) {
        return typeof value === "string" && /^#[0-9a-f]{6}$/i.test(value)
            ? value.toUpperCase()
            : null;
    }

    async function apiRequest(action, color) {
        const response = await fetch("color_picker_api.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken
            },
            body: JSON.stringify({ action, color })
        });
        let result;
        try {
            result = await response.json();
        } catch (error) {
            throw new Error(`Server mengirim respons yang tidak valid (HTTP ${response.status}). Periksa konfigurasi database dan log server.`);
        }

        if (!response.ok || result.success !== true) {
            throw new Error(result.message || "Permintaan ke database gagal.");
        }

        return result;
    }

    function setMessage(text, isError = false) {
        message.textContent = text;
        message.classList.toggle("error", isError);
    }

    function updateCurrentColor(color) {
        picker.value = color;
        preview.style.backgroundColor = color;
        hexLabel.textContent = color.toUpperCase();
    }

    function addEmptyMessage(list, text) {
        const empty = document.createElement("p");
        empty.className = "empty-message";
        empty.textContent = text;
        list.appendChild(empty);
    }

    function createColorItem(color, { canSave, canRemove }) {
        const item = document.createElement("article");
        item.className = "color-item";

        const swatch = document.createElement("button");
        swatch.type = "button";
        swatch.className = "swatch";
        swatch.style.backgroundColor = color;
        swatch.setAttribute("aria-label", `Gunakan warna ${color}`);
        swatch.title = `Gunakan ${color}`;
        swatch.addEventListener("click", () => {
            updateCurrentColor(color);
            setMessage(`${color} digunakan kembali. Pilih warna untuk mencatatnya lagi di history.`);
        });

        const hex = document.createElement("span");
        hex.className = "item-hex";
        hex.textContent = color;

        const actions = document.createElement("div");
        actions.className = "item-actions";

        if (canSave) {
            const saveButton = document.createElement("button");
            saveButton.type = "button";
            saveButton.className = "text-button";
            saveButton.textContent = "Simpan";
            saveButton.setAttribute("aria-label", `Simpan ${color} ke Color Library`);
            saveButton.addEventListener("click", () => addToLibrary(color));
            actions.appendChild(saveButton);
        }

        if (canRemove) {
            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.className = "text-button remove-button";
            removeButton.textContent = "Hapus";
            removeButton.setAttribute("aria-label", `Hapus ${color} dari Color Library`);
            removeButton.addEventListener("click", () => removeFromLibrary(color));
            actions.appendChild(removeButton);
        }

        item.append(swatch, hex, actions);
        return item;
    }

    function renderLists() {
        historyList.replaceChildren();
        libraryList.replaceChildren();
        historyCount.textContent = `${history.length} / ${maxHistory}`;
        libraryCount.textContent = `${library.length} warna`;

        if (library.length === 0) {
            addEmptyMessage(libraryList, "Belum ada warna favorit. Simpan warna pilihanmu dari atas atau dari history.");
        } else {
            library.forEach((color) => {
                libraryList.appendChild(createColorItem(color, { canSave: false, canRemove: true }));
            });
        }

        if (history.length === 0) {
            addEmptyMessage(historyList, "Warna yang kamu pilih akan muncul di sini.");
        } else {
            history.forEach((color) => {
                historyList.appendChild(
                    createColorItem(color, { canSave: !library.includes(color), canRemove: false })
                );
            });
        }
    }

    async function addToHistory(color) {
        try {
            const result = await apiRequest("add_history", color);
            history = result.history;
            renderLists();
            setMessage(`${color} ditambahkan ke history.`);
        } catch (error) {
            setMessage(`History tidak dapat disimpan ke database: ${error.message}`, true);
        }
    }

    async function addToLibrary(color) {
        if (library.includes(color)) {
            setMessage(`${color} sudah ada di Color Library.`);
            return;
        }

        try {
            const result = await apiRequest("add_library", color);
            library = result.library;
            renderLists();
            setMessage(`${color} disimpan ke Color Library.`);
        } catch (error) {
            setMessage(`Color Library tidak dapat disimpan ke database: ${error.message}`, true);
        }
    }

    async function removeFromLibrary(color) {
        try {
            const result = await apiRequest("remove_library", color);
            library = result.library;
            renderLists();
            setMessage(`${color} dihapus dari Color Library.`);
        } catch (error) {
            setMessage(`Color Library tidak dapat diperbarui di database: ${error.message}`, true);
        }
    }

    updateCurrentColor(picker.value.toUpperCase());
    renderLists();

    async function loadSavedColors() {
        try {
            const result = await apiRequest("list");
            history = result.history;
            library = result.library;
            renderLists();
        } catch (error) {
            setMessage(`Warna tersimpan tidak dapat dimuat dari database: ${error.message}`, true);
        }
    }

    loadSavedColors();

    picker.addEventListener("input", () => {
        updateCurrentColor(picker.value.toUpperCase());
    });

    picker.addEventListener("change", () => {
        addToHistory(picker.value.toUpperCase());
    });

    document.getElementById("simpanWarna").addEventListener("click", () => {
        addToLibrary(picker.value.toUpperCase());
    });

    document.getElementById("salinWarna").addEventListener("click", async () => {
        try {
            await navigator.clipboard.writeText(hexLabel.textContent);
            setMessage("Kode warna berhasil disalin.");
        } catch (error) {
            setMessage("Kode warna tidak dapat disalin. Silakan salin kode secara manual.", true);
        }
    });
})();
