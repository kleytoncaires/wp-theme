$(function () {
    // Sticky header
    var observer = new IntersectionObserver(
        function (entries) {
            // no intersection with screen
            if (0 === entries[0].intersectionRatio) {
                document.querySelector('#header-bar').classList.add('sticky')
            }

            // fully intersects with screen
            else if (1 === entries[0].intersectionRatio) {
                document.querySelector('#header-bar').classList.remove('sticky')
            }
        },
        {
            threshold: [0, 1],
        }
    )

    observer.observe(document.querySelector('#sticky-menu-top'))

    // Masks
    var maskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11
                ? '(00) 00000-0000'
                : '(00) 0000-00009'
        },
        options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(maskBehavior.apply({}, arguments), options)
            },
        }
    $('.form-tel').mask(maskBehavior, options)
    $('.form-cep').mask('00000-000')

    // CEP autofill
    $('#form-cep').on('focusout', function () {
        $.ajax({
            url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
            dataType: 'json',
            success: function (resposta) {
                $('#form-endereco').val(resposta.logradouro)
                $('#form-complemento').val(resposta.complemento)
                $('#form-bairro').val(resposta.bairro)
                $('#form-cidade').val(resposta.localidade)
                $('#form-uf').val(resposta.uf)
                $('#form-numero').focus()
            },
        })
    })

    // Get container offset
    offsetWidth()
    $(window).on('resize', function () {
        offsetWidth()
    })

    function offsetWidth() {
        var containerOffset = $('.container').offset().left
        $('.container-offset').css('width', containerOffset)
    }

    // Fancybox
    Fancybox.bind()
})
