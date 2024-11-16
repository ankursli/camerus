<table class="widefat ssm ssm_table" style="margin-bottom: 20px;">
    <tbody>
    <div>
        <label>
            <input type="checkbox" name="rent-order-xml-send"> Cocher pour envoyer le XML à Rent+ après enregistrement
        </label>
    </div>
    <label for="rent-order-xml" style="color: red">Faire attention aux balises XML</label>
    <textarea name="rent-order-xml" id="rent-order-xml" style="width: 100%; box-sizing:border-box;" rows="45">
        {!! $content !!}
    </textarea>
    </tbody>
</table>