<?php include("../includes/header.php"); ?>

<form id="create-record-from" class="pure-form pure-form-stacked">
    <fieldset>

        <label for="record-title"><?php echo _('Título del registro'); ?>
            <input id="record-title" type="text" required/>
        </label>


        <label for="alternate-title"><?php echo _('Título alternativo'); ?>
            <input id="alternate-title" type="text"/>
        </label>
        <label for="location"><?php echo _('Ubicación (Inserte URL)'); ?>
            <input id="location" type="text" required/>
        </label>

        <label for="URL de comprobación">
            <span id="checkurl" class="checkurl_img_wrapper"><i alt="Check URL" title="URL de comprobación" border="0"
                                                                class="fa fa-globe fa-2x clickable"></i></span>
        </label>

        <label for="description"><?php echo _('Descripción'); ?>
            <textarea id="description"></textarea>
        </label>

        <button id="add-record" class="pure-button pure-button-primary"
                type="submit"><?php echo _('Crear registro'); ?></button>
    </fieldset>
    <div class="notify"></div>
</form>

<script>
    $('#create-record-from').on('submit', function (e) {
        if (!this.checkValidity()) {
            console.log(this.checkValidity());
            e.preventDefault();
        } else {
            e.preventDefault();

            record.title = $('#record-title').val();
            record.description = $('#description').val();
            record.pre = $('#prefix').val();
            location.location = $('#location').val()
            record.locations.push(location);

            $.post("<?php echo getControlURL(); ?>/records/insert_record.php", JSON.stringify(record), function (data) {
                res = JSON.parse(data);
                if (res.response !== "error") {
                    $('.notify').html("<a href='" + res.response + "'>" + record.title + "</a>")
                } else {
                    $('.notify').html("<?php echo _('Se ha producido un error al insertar el registro'); ?>")
                }
            });
        }
    });


    $('#checkurl').on('click', function () {
        var location = $('#location').val();

        $.post("<?php echo getControlURL(); ?>/records/record_bits.php", {
            type: "check_url",
            checkurl: location
        }, function (data) {
            $('#checkurl').html(data);
        });

    });
</script>

</body>
</html>