//Account Page
function truncateAfterWords(className, numberOfWords) {
    const cells = document.querySelectorAll(`.${className}`);
    cells.forEach(cell => {
        const text = cell.textContent.trim();
        const words = text.split(/\s+/);
        if (words.length > numberOfWords) {
            const truncatedText = words.slice(0, numberOfWords).join(' ') + '...';
            cell.textContent = truncatedText;
        }
    });
}
truncateAfterWords('truncate-text', 2);


// Card Details Page
let tabs = document.querySelectorAll('.tab');
let tabPanes = document.querySelectorAll('.tab-pane');
tabs.forEach((tab, index) => {
  tab.addEventListener('click', function() {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    tabPanes.forEach(pane => pane.style.display = 'none');
    tabPanes[index].style.display = 'block';
  });
});

//Card Details CARD FLIP JS
let isSensitiveVisible = false; // State to track visibility

document.getElementById('toggleButton').addEventListener('click', function () {
    const cardNumber = document.getElementById('cardNumberOnCard');
    const cvv = document.getElementById('cvvOnCard');
    const cardSensitive = cardNumber.getAttribute("card-sensitive");
    const cvvSensitive = cvv.getAttribute("cvv-sensitive");
    const toggleIcon = document.getElementById('toggleIcon');

    // Toggle the visibility state
    isSensitiveVisible = !isSensitiveVisible;

    if (isSensitiveVisible) {
        // Show sensitive data
        cardNumber.textContent = cardSensitive;
        cvv.textContent = cvvSensitive;
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        // Show masked data
        cardNumber.textContent = '**** **** **** ****';
        cvv.textContent = '***';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
});



// CARD Details SENSITIVE DATA JS
document.querySelectorAll('.toggle-visibility').forEach(icon => {
    icon.addEventListener('click', function() {
        const td = this.closest('tr').querySelector('.senstive-data-gap');
        const sensitiveData = td.getAttribute('data-sensitive');

        // Toggle directly between sensitive data and masked version
        if (td.innerText === sensitiveData) {
            // Create a masked version: replace all but last 4 digits with '*'
            const maskedValue = '***********';
            td.innerText = maskedValue; // Show masked text
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        } else {
            td.innerText = sensitiveData; // Show sensitive data
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        }
    });
});


// Card Details Page (LIMITS SECTION)
function showContent(tab) {
    const tabButtons = document.querySelectorAll('.day-tab-button');
    const tabContents = document.querySelectorAll('.day-tab-content');
    tabButtons.forEach((button) => {
        button.classList.remove('active');
    });
    tabContents.forEach((content) => {
        content.classList.remove('active');
    });
    document.querySelector(`.day-tab-button[onclick="showContent('${tab}')"]`).classList.add('active');
    document.getElementById(tab).classList.add('active');
}
