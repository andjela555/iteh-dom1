<?php
include 'header.php';
?>

<div class="container mt-5 bg-white pb-5 border rounded transparent">
    <h1 class="text-center">Pretraga repertoara</h1>
    <div class="row mt-4">
        <div class="col-4">
            <select class="form-control" id="film">
                <option value="0">
                    Svi filmovi
                </option>
            </select>
        </div>
        <div class="col-5">
            <input type="text" placeholder="Pretrazi..." id='pretraga' class="form-control">
        </div>
        <div class="col-3">
            <input type="date" id='datum' class="form-control">
        </div>
    </div>
    <table class="mt-3 table table-dark">
        <thead>
            <tr>
                <th>Naziv filma</th>
                <th>Cena karte</th>
                <th>Trajanje filma</th>
                <th>Ocena filma</th>
                <th>Sala</th>
                <th>Pocetak</th>
            </tr>
        </thead>
        <tbody id='repertoar'>

        </tbody>
    </table>
</div>
<script>
    let prikazi = [];

    $(document).ready(function() {

        $('#film').change(function() {
            popuniTabelu();
        })

        $('#pretraga').change(function() {
            popuniTabelu();
        })

        $('#datum').change(function() {
            popuniTabelu();
        })
        $.getJSON('./server/index.php?akcija=prikaz.read', function(res) {
            if (!res.status) {
                alert(res.error);
                return;
            }
            prikazi = res.data;
            popuniTabelu();
        });
        $.getJSON('./server/index.php?akcija=film.read', function(res) {
            if (!res.status) {
                alert(res.error);
                return;
            }
            for (let film of res.data) {
                $('#film').append(`
                <option value='${film.id}'>${film.naziv}</option>
                `)
            }
        })
    })

    function popuniTabelu() {
        const filmId = Number($('#film').val());
        const datumStr = $('#datum').val();
        const datum = new Date(datumStr);
        const pretraga = $('#pretraga').val();
        const filtrirani = prikazi.filter(function(element) {
            const elementDatum = new Date(element.datum);

            return (filmId == 0 || element.film.id == filmId) &&
                (element.film.naziv.includes(pretraga) || element.sala.naziv.includes(pretraga)) &&
                (datumStr === '' || (datum.getDate() === elementDatum.getDate() && datum.getMonth() === elementDatum.getMonth() && elementDatum.getFullYear() === datum.getFullYear()))
        })
        $('#repertoar').html('');
        for (let prikaz of filtrirani) {
            $('#repertoar').append(`
                <tr>
                    <td>${prikaz.film.naziv}</td>
                    <td>${prikaz.cena}</td>
                    <td>${prikaz.film.trajanje}</td>
                    <td>${prikaz.film.ocena}</td>
                    <td>${prikaz.sala.naziv}</td>
                    <td>${prikaz.datum}</td>
                </tr>
            `)
        }
    }
</script>
<?php
include 'footer.php';
?>