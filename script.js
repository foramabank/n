document.addEventListener('DOMContentLoaded', () => {
    const amountButtons = document.querySelectorAll('.amount-btn');
    const submitButton = document.getElementById('submit-btn');
    const loanForm = document.getElementById('loan-form');
    const acceptTermsCheckbox = document.getElementById('accept-terms');
    const confirmationMessage = document.getElementById('confirmation-message');
    const acceptTermsError = document.getElementById('accept-terms-error');
    const amountError = document.getElementById('amount-error');
    const dateInput = document.getElementById('date');
    const daySelect = document.getElementById('day');
    const monthSelect = document.getElementById('month');
    const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    let selectedAmount = '';

    // Fonction de changement de langue et de mise à jour du drapeau
    function changeLanguage(lang) {
        const flagMap = {
            'fr': 'path_to_french_flag.png',
            'en': 'path_to_english_flag.png',
            'es': 'path_to_spanish_flag.png',
            'pt': 'path_to_portuguese_flag.png',
            'it': 'path_to_italian_flag.png',
            'da': 'path_to_danish_flag.png',
            'de': 'path_to_german_flag.png',
            'ga': 'path_to_irish_flag.png',
            'no': 'path_to_norwegian_flag.png',
            'ru': 'path_to_russian_flag.png'
        };
        
        const currentLanguageElement = document.getElementById('current-language');
        currentLanguageElement.innerHTML = `<img src="${flagMap[lang]}" alt="Langue actuelle" width="24" height="16">`;

        switch (lang) {
            case 'fr':
                window.location.href = 'index_fr.html';
                break;
            case 'es':
                window.location.href = 'index_es.html';
                break;
            case 'pt':
                window.location.href = 'index_pt.html';
                break;
            case 'it':
                window.location.href = 'index_it.html';
                break;
            case 'da':
                window.location.href = 'index_da.html';
                break;
            case 'de':
                window.location.href = 'index_de.html';
                break;
            case 'ga':
                window.location.href = 'index_ga.html';
                break;
            case 'no':
                window.location.href = 'index_no.html';
                break;
            case 'ru':
                window.location.href = 'index_ru.html';
                break;
            default:
                window.location.href = 'index_en.html';
        }
    }

    // Détection de la langue de l'utilisateur
    const userLang = navigator.language || navigator.userLanguage;
    const lang = userLang.split('-')[0]; // obtenir les deux premiers caractères de la langue (ex : 'en', 'fr')

    changeLanguage(lang);

    function populateDays(month) {
        const days = daysInMonth[month - 1];
        daySelect.innerHTML = '';
        for (let i = 1; i <= days; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            daySelect.appendChild(option);
        }
    }

    function updateDateInput() {
        const day = daySelect.value;
        const month = monthSelect.value;
        const date = `2025-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        dateInput.value = `${day} ${new Date(2025, month - 1, day).toLocaleString('fr', { month: 'long' })} 2025`;
    }

    monthSelect.addEventListener('change', () => {
        const selectedMonth = parseInt(monthSelect.value);
        populateDays(selectedMonth);
        updateDateInput();
    });

    daySelect.addEventListener('change', updateDateInput);

    // Initialise les jours pour le mois par défaut
    populateDays(parseInt(monthSelect.value));
    updateDateInput();

    amountButtons.forEach(button => {
        button.addEventListener('click', () => {
            selectedAmount = button.dataset.value;
            amountButtons.forEach(btn => btn.classList.remove('selected')); // Retire la classe selected des autres boutons
            button.classList.add('selected'); // Ajoute la classe selected au bouton cliqué
            amountError.style.display = 'none'; // Masquer le message d'erreur pour le montant
        });
    });

    submitButton.addEventListener('click', () => {
        submitButton.style.backgroundColor = '#6d4aff';
        submitButton.style.color = 'white';
    });

    loanForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(loanForm);
        formData.append('amount', selectedAmount);
        formData.append('date', dateInput.value);
        const data = Object.fromEntries(formData);

        // Vérifications supplémentaires
        let isValid = true;
        if (!acceptTermsCheckbox.checked) {
            acceptTermsError.textContent = 'Vous devez cocher cette case avant de valider la demande.';
            acceptTermsError.style.display = 'block';
            isValid = false;
        } else {
            acceptTermsError.style.display = 'none';
        }

        if (!selectedAmount) {
            amountError.textContent = 'Vous devez choisir la somme que vous souhaitez prêter avant de valider la demande.';
            amountError.style.display = 'block';
            isValid = false;
        } else {
            amountError.style.display = 'none';
        }

        if (isValid) {
            document.getElementById('user-data').textContent = JSON.stringify(data, null, 2);
            document.getElementById('user-info').classList.remove('hidden');

            const contact = formData.get('contact');
            const isEmail = contact.includes('@');
            const url = isEmail ? 'send_email.php' : 'send_whatsapp.php';

            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => alert(result))
            .catch(error => console.error('Error:', error));

            // Réinitialiser les boutons après la soumission
            amountButtons.forEach(btn => btn.classList.remove('selected'));
            selectedAmount = '';

            // Masquer le formulaire et afficher le message de confirmation
            loanForm.style.display = 'none';
            confirmationMessage.style.display = 'block';
        }
    });
});
