document.addEventListener("DOMContentLoaded", function () {
    const fields = [
        "roads_area",
        "park_area",
        "cemetery_area",
        "mosque_area",
        "social_waste_area",
        "disposable_area",
        "commercial_area",
        "residential_area",
        "public_buildings_area",
        "miscellaneous_area"
    ];

    const totalField = document.getElementById("total_area");

    function calculateTotal() {
        let total = 0;

        fields.forEach(function (field) {
            const input = document.getElementById(field);
            if (input) {
                let value = parseFloat(input.value);
                if (!isNaN(value)) {
                    total += value;
                }
            }
        });

        totalField.value = total; // 2 decimal places
    }

    // Attach input listener to each field
    fields.forEach(function (field) {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener("input", calculateTotal);
        }
    });

    // Calculate once on page load (for edit form)
    calculateTotal();
});
