// ================================
// DATA
// ================================

let colors = [
    "#7F5AF0",
    "#2CB67D"
];


// ================================
// ELEMENT
// ================================

const gradientPreview =
    document.getElementById("gradientPreview");

const gradientText =
    document.getElementById("gradientText");

const colorInputs =
    document.getElementById("colorInputs");

const gradientType =
    document.getElementById("gradientType");

const angle =
    document.getElementById("angle");

const angleValue =
    document.getElementById("angleValue");


// ================================
// DISPLAY COLOR INPUT
// ================================

function renderColorInputs() {

    colorInputs.innerHTML = "";

    colors.forEach((color, index) => {

        const div =
            document.createElement("div");

        div.className = "color-input";

        div.innerHTML = `

            <input
                type="color"
                value="${color}"
                onchange="updateColor(${index}, this.value)"
            >

            <input
                class="hex-input"
                type="text"
                value="${color}"
                onchange="updateColor(${index}, this.value)"
            >

            <button
                class="remove-color"
                onclick="removeColor(${index})"
            >
                ×
            </button>

        `;

        colorInputs.appendChild(div);

    });

}


// ================================
// UPDATE COLOR
// ================================

function updateColor(index, value) {

    if (!value.startsWith("#")) {
        value = "#" + value;
    }

    colors[index] = value;

    updateGradient();
}


// ================================
// ADD COLOR
// ================================

function addColor() {

    if (colors.length >= 6) {

        alert("Maksimal 6 warna.");

        return;
    }

    colors.push(randomColor());

    renderColorInputs();

    updateGradient();
}


// ================================
// REMOVE COLOR
// ================================

function removeColor(index) {

    if (colors.length <= 2) {

        alert("Minimal harus ada 2 warna.");

        return;
    }

    colors.splice(index, 1);

    renderColorInputs();

    updateGradient();
}


// ================================
// RANDOM COLOR
// ================================

function randomColor() {

    const letters =
        "0123456789ABCDEF";

    let color = "#";

    for (let i = 0; i < 6; i++) {

        color +=
            letters[
                Math.floor(
                    Math.random() *
                    letters.length
                )
            ];

    }

    return color;
}


// ================================
// RANDOM GRADIENT
// ================================

function randomGradient() {

    colors = [];

    const numberOfColors =
        Math.floor(
            Math.random() * 3
        ) + 2;

    for (
        let i = 0;
        i < numberOfColors;
        i++
    ) {

        colors.push(
            randomColor()
        );

    }

    angle.value =
        Math.floor(
            Math.random() * 360
        );

    angleValue.textContent =
        angle.value + "°";

    renderColorInputs();

    updateGradient();
}


// ================================
// UPDATE GRADIENT
// ================================

function updateGradient() {

    let gradient;

    if (gradientType.value === "linear") {

        gradient =
            `linear-gradient(${angle.value}deg, ${colors.join(", ")})`;

    } else {

        gradient =
            `radial-gradient(circle, ${colors.join(", ")})`;

    }

    gradientPreview.style.background =
        gradient;

    gradientText.textContent =
        gradient;

}


// ================================
// ANGLE
// ================================

angle.addEventListener(
    "input",
    function () {

        angleValue.textContent =
            this.value + "°";

        updateGradient();

    }
);


// ================================
// GRADIENT TYPE
// ================================

gradientType.addEventListener(
    "change",
    updateGradient
);


// ================================
// COPY CSS
// ================================

function copyGradient() {

    const css =
        gradientText.textContent;

    navigator.clipboard.writeText(css);

    alert(
        "CSS Gradient berhasil dicopy!"
    );

}


// ================================
// PALETTE
// ================================

function generatePalette() {

    const paletteContainer =
        document.getElementById(
            "paletteContainer"
        );

    paletteContainer.innerHTML = "";

    for (let i = 0; i < 5; i++) {

        const color =
            randomColor();

        const card =
            document.createElement("div");

        card.className =
            "palette-card";

        card.style.background =
            color;

        card.innerHTML = `
            <span>${color}</span>
        `;

        card.onclick = function () {

            navigator.clipboard.writeText(
                color
            );

            alert(
                color +
                " berhasil dicopy!"
            );

        };

        paletteContainer.appendChild(
            card
        );

    }

}


// ================================
// INITIAL
// ================================

renderColorInputs();

updateGradient();

generatePalette();