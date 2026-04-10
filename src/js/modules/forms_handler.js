// forms_handler.js - обработка формы с AboutUs.html

$(document).ready(function() {
    
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = {
            name: $('input[name="name"]').val(),
            position: $('input[name="position"]').val(),
            phone: $('input[name="phone"]').val(),
            email: $('input[name="email"]').val(),
            message: $('textarea[name="message"]').val()
        };
        
        let isChecked = $('input[name="privacy_policy"]').prop('checked');
        if (!isChecked) {
            showToast('Пожалуйста, согласитесь с правилами обработки персональных данных', 'error');
            return;
        }
        
        if (!formData.name || !formData.position || !formData.phone || !formData.email) {
            showToast('Пожалуйста, заполните все обязательные поля', 'error');
            return;
        }
        
        let submitBtn = $(this).find('.black_button');
        let originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<h4>Отправка...</h4>');
        
        $.ajax({
            url: 'send_form.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    $('#contactForm')[0].reset();
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function() {
                showToast('Ошибка соединения. Попробуйте позже.', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    function showToast(message, type) {
        $('.toast-message').remove();
        
        let bgColor = type === 'success' ? '#4CAF50' : '#f44336';
        
        let toast = $('<div class="toast-message">')
            .text(message)
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'background': bgColor,
                'color': 'white',
                'padding': '12px 20px',
                'border-radius': '4px',
                'font-size': '14px',
                'font-family': 'Arial, sans-serif',
                'z-index': '9999',
                'box-shadow': '0 2px 8px rgba(0,0,0,0.2)',
                'animation': 'slideInRight 0.3s ease'
            });
        
        $('body').append(toast);
        
        // Добавляем анимацию если её нет
        if (!$('#toast-styles').length) {
            $('<style id="toast-styles">')
                .html(`
                    @keyframes slideInRight {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `)
                .appendTo('head');
        }
        
        setTimeout(function() {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});