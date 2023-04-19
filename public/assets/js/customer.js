//javascript do người dùng tự viết
// document.getElementById("cancelButton").addEventListener("click", function() {
//     window.history.back();
// });


$(document).ready(function() {
    $("#cancelButton").click(function() {
        window.history.back();
    });
});

// Toast customer
function toast({title = '', message = '', type = '', duration = 3000}) {
    const main = document.getElementById('toast-customer');
    if(main){
        const toast = document.createElement('div');

        // Auto remove toast
        const autoRemoveId = setTimeout(function() {
            main.removeChild(toast);
        }, duration + 1000);

        // Remove toast when click
        toast.onclick = function(e) {
            if(e.target.closest('.toast-customer__close') ){
                main.removeChild(toast);
                clearTimeout(autoRemoveId);
            }
        }

        const icons = {
            success: 'fas fa-check-circle',
            info: 'fas fa-info-circle',
            warning: 'fas fa-exclamation-circle',
            error: 'fas fa-exclamation-circle',
        };
        const icon = icons[type];
        const delay = (duration /1000).toFixed(2);

        toast.classList.add('toast-customer', `toast--${type}`);
        toast.style.animation = `slideInLeft ease .3s, hideOut linear 1s ${delay}s forwards`;
        toast.innerHTML = `
            <div class="toast-customer__icon">
                <i class="${icon}"></i>
            </div>
            <div class="toast-customer__body">
                <h3 class="toast-customer__title">${title}</h3>
                <p class="toast-customer__msg">${message}</p>
            </div>
            <div class="toast-customer__close">
                <i class="fas fa-times"></i>
            </div>
        `;
        main.appendChild(toast);

    }
    
}


function showToastSuccess (){
    toast({
    title: 'Success',
    message: 'Thoong bao thanh cong',
    type: 'info',
    duration: 9000
    })
}

function showToast(type, message ){
    toast({
        title: type.charAt(0).toUpperCase() + type.slice(1),
        message: message,
        type: type,
        duration: 9000
    })
}