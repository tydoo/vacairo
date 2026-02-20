const emailNotify = {
    form: null,
    btn: null,
    emailValue: null,

    init: function () {
        this.form = document.getElementById('notify-form');
        this.btn = document.getElementById('notify-btn');

        if (this.btn && this.form) {
            this.btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.emailValue = document.getElementById('email').value;
                if (this.checkEmailIsValid()) {
                    this.addLoaderInBtn();
                    this.run();
                } else {
                    alert('Veuillez entrer une adresse email valide.');
                }
            });


            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.emailValue = document.getElementById('email').value;
                if (this.checkEmailIsValid()) {
                    this.addLoaderInBtn();
                    this.run();
                } else {
                    alert('Veuillez entrer une adresse email valide.');
                }
            });

        }
    },
    checkEmailIsValid: function () {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(this.emailValue);
    },
    addLoaderInBtn: function () {
        const loader = document.createElement('span');
        loader.classList.add('animate-spin');
        loader.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle opacity="0.5" cx="10" cy="10" r="8.75" stroke="white" stroke-width="2.5"></circle>
            <mask id="path-2-inside-1_3755_26472" fill="white">
            <path d="M18.2372 12.9506C18.8873 13.1835 19.6113 12.846 19.7613 12.1719C20.0138 11.0369 20.0672 9.86319 19.9156 8.70384C19.7099 7.12996 19.1325 5.62766 18.2311 4.32117C17.3297 3.01467 16.1303 1.94151 14.7319 1.19042C13.7019 0.637155 12.5858 0.270357 11.435 0.103491C10.7516 0.00440265 10.179 0.561473 10.1659 1.25187V1.25187C10.1528 1.94226 10.7059 2.50202 11.3845 2.6295C12.1384 2.77112 12.8686 3.02803 13.5487 3.39333C14.5973 3.95661 15.4968 4.76141 16.1728 5.74121C16.8488 6.721 17.2819 7.84764 17.4361 9.02796C17.5362 9.79345 17.5172 10.5673 17.3819 11.3223C17.2602 12.002 17.5871 12.7178 18.2372 12.9506V12.9506Z"></path>
            </mask>
            <path d="M18.2372 12.9506C18.8873 13.1835 19.6113 12.846 19.7613 12.1719C20.0138 11.0369 20.0672 9.86319 19.9156 8.70384C19.7099 7.12996 19.1325 5.62766 18.2311 4.32117C17.3297 3.01467 16.1303 1.94151 14.7319 1.19042C13.7019 0.637155 12.5858 0.270357 11.435 0.103491C10.7516 0.00440265 10.179 0.561473 10.1659 1.25187V1.25187C10.1528 1.94226 10.7059 2.50202 11.3845 2.6295C12.1384 2.77112 12.8686 3.02803 13.5487 3.39333C14.5973 3.95661 15.4968 4.76141 16.1728 5.74121C16.8488 6.721 17.2819 7.84764 17.4361 9.02796C17.5362 9.79345 17.5172 10.5673 17.3819 11.3223C17.2602 12.002 17.5871 12.7178 18.2372 12.9506V12.9506Z" stroke="white" stroke-width="4" mask="url(#path-2-inside-1_3755_26472)"></path>
        </svg>
    `;

        this.btn.innerHTML = '';
        this.btn.appendChild(loader);
    },
    run: function () {
        fetch('/api/v1/email-notify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: this.emailValue })
        })
            .then(response => {
                if (response.ok) {
                    const successMessage = document.createElement('div');
                    successMessage.innerHTML = `
                        <div class="rounded-xl text-left border border-success-500 bg-success-50 p-4 dark:border-success-500/30 dark:bg-success-500/15">
                            <div class="flex items-start gap-3">
                                <div class="-mt-0.5 text-success-500">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill=""></path>
                                </svg>
                                </div>

                                <div>
                                <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                                    Votre adresse email a été enregistrée avec succès ! Vous serez notifié dès que le service sera disponible.
                                </h4>
                                </div>
                            </div>
                            </div>
                    `;
                    this.form.parentNode.replaceChild(successMessage, this.form);
                } else {
                    alert('Failed to send email notification.');
                }
            })
            .catch(error => {
                alert('An error occurred while sending the email notification.');
            });
    }
};

emailNotify.init();
