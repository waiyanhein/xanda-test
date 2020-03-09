$(function () {
    $('body').on('click', '.btn-delete', function () {
        var id = $(this).data('id')
        var confirmed = confirm('Are you sure to delete?')
        if (! confirmed) {
            return
        }
        var url = $(this).data('url')

        axios.post(url, { "_token": $('#csrf-token').attr('content') }).then(function () {
            location.reload()
        }).catch(function () {
            alert('Something went wrong')
        })
    })

    $('body').on('click', '.btn-add-armament', function () {
        var $container = $('#armaments');
        var rowIndex = $('.armament-row').length

        $container.append("<div class='armament-row'><br /><div><input placeholder='title' name='armaments[" +rowIndex+ "][title]'> <input placeholder='qty' type='number' name='armaments["+rowIndex+"][qty]'> <button class='btn-remove-armament' type='button'>remove</button></div></div>")
    })

    $('body').on('click', '.btn-remove-armament', function () {
        $(this).parents('.armament-row').remove()
    })
})
