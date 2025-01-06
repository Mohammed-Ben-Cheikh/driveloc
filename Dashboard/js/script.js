let rowsPerPage = 5; // Nombre de lignes par page
forAuto()
function auto() {
    let autodetect = document.getElementById('rowsPerPageSelect').value
    rowsPerPage = autodetect;
    forAuto();
}
function forAuto() {
    const tableBody = document.querySelector("table tbody");
    const rows = Array.from(tableBody.querySelectorAll("tr"));
    const paginationNumbers = document.getElementById("paginationNumbers");
    const prevButton = document.getElementById("prevPage");
    const nextButton = document.getElementById("nextPage");
    const showingInfo = document.querySelector(".flex.items-center.col-span-3"); // Sélection de la section "Showing X-Y of Z"

    let currentPage = 1;
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    function updateTable() {
        rows.forEach((row, index) => {
            row.style.display =
                index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage
                    ? ""
                    : "none";
        });

        updatePaginationButtons();
        updateShowingInfo();
    }

    function updatePaginationButtons() {
        paginationNumbers.innerHTML = "";

        // Afficher les premières et dernières pages et les pages autour de la page courante
        const maxPagesToShow = 5;
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        if (currentPage <= 3) {
            endPage = Math.min(maxPagesToShow, totalPages);
        } else if (currentPage >= totalPages - 2) {
            startPage = Math.max(totalPages - maxPagesToShow + 1, 1);
        }

        // Ajouter les pages avant la page courante
        for (let i = startPage; i <= endPage; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = `px-3 py-1 rounded-md ${i === currentPage ? "bg-purple-600 text-white" : "focus:outline-none"
                }`;
            button.addEventListener("click", () => {
                currentPage = i;
                updateTable();
            });
            paginationNumbers.appendChild(button);
        }

        // Ajouter les points de suspension si nécessaire
        if (startPage > 1) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "px-3 py-1";
            paginationNumbers.insertBefore(dots, paginationNumbers.firstChild);
        }
        if (endPage < totalPages) {
            const dots = document.createElement("span");
            dots.textContent = "...";
            dots.className = "px-3 py-1";
            paginationNumbers.appendChild(dots);
        }

        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;
    }

    function updateShowingInfo() {
        const start = (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, rows.length);
        showingInfo.textContent = `Showing ${start}-${end} of ${rows.length}`;
    }

    prevButton.addEventListener("click", () => {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    nextButton.addEventListener("click", () => {
        if (currentPage < totalPages) {
            currentPage++;
            updateTable();
        }
    });

    updateTable();

};

function openModal() {
    document.getElementById('menuModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('menuModal').classList.add('hidden');
}

function addIngredient(btn) {
    const container = btn.closest('.ingredients-container');
    const platIndex = container.closest('.plat-entry').getAttribute('data-index');
    const newIngredient = document.createElement('div');
    newIngredient.className = 'ingredient-entry flex gap-2';
    newIngredient.innerHTML = `
<input type="text" name="ingredients[${platIndex}][]" placeholder="Ingredient Name" class="flex-1 p-2 border rounded">
<button type="button" onclick="removeIngredient(this)" class="bg-red-500 text-white px-3 rounded">-</button>
`;
    container.appendChild(newIngredient);
}

function removeIngredient(btn) {
    btn.closest('.ingredient-entry').remove();
}

function addPlat() {
    const container = document.getElementById('platsContainer');
    const platIndex = container.getElementsByClassName('plat-entry').length;
    const newPlat = document.createElement('div');
    newPlat.className = 'plat-entry border p-4 rounded';
    newPlat.setAttribute('data-index', platIndex);
    newPlat.innerHTML = `
<input type="text" name="plat_nom[]" placeholder="Plat Name" required class="w-full p-2 border rounded mb-2">
<input type="number" name="plat_prix[]" placeholder="Plat Price" step="0.01" required class="w-full p-2 border rounded mb-2">
<textarea name="plat_description[]" placeholder="Plat Description" class="w-full p-2 border rounded mb-2"></textarea>
<div class="ingredients-container space-y-2">
<div class="ingredient-entry flex gap-2">
<input type="text" name="ingredients[${platIndex}][]" placeholder="Ingredient Name" class="flex-1 p-2 border rounded">
<button type="button" onclick="addIngredient(this)" class="bg-blue-500 text-white px-3 rounded">+</button>
</div>
</div>
`;
    container.appendChild(newPlat);
}

document.getElementById('menuForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    try {
        const response = await fetch('add_menu.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            location.reload();
        } else {
            alert('Error saving menu');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error saving menu');
    }
});
