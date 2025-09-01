// <!-- הסקריפט שממין לידים באזור אישי של מתוו -->
document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("filter-by-apartment");
    const allLeads = document.querySelectorAll('.card[data-apartment-id]');
    const noLeadsMsg = document.getElementById("no-leads-message");

    // הפעלת select2
    if (typeof jQuery !== "undefined" && jQuery().select2) {
        jQuery('.select2').select2();
    }

    // סינון בלייב
    select.addEventListener("change", function () {
        const selectedId = select.value;
        let hasVisible = false;

        allLeads.forEach(function (card) {
            const cardApartment = card.getAttribute('data-apartment-id');
            const match = !selectedId || cardApartment === selectedId;
            card.style.display = match ? "block" : "none";
            if (match) hasVisible = true;
        });

        if (noLeadsMsg) {
            noLeadsMsg.style.display = hasVisible ? "none" : "block";
        }
    });
});
