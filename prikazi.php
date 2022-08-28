<?php
include 'header.php';
?>

<div class="container mt-4 bg-white pb-5 border rounded transparent">
    <h1>Repertoar</h1>
    <div class="row mt-3">
        <div class="col-8">
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
        <div class="col-4">
            <h3 class="text-center">Kreiraj termin za film</h3>
            <form id='forma'>
                <label>Cena karte</label>
                <input class="form-control" type="number" min='0' id='cena'>
                <label>Datum prikaza</label>
                <input class="form-control" type="datetime-local" id='datum'>
                <label>Film</label>
                <select class="form-control" id='film'></select>
                <label>Sala</label>
                <select class="form-control" id='sala'></select>
                <button class="btn btn-primary form-control mt-2">Kreiraj</button>
            </form>
        </div>
    </div>
</div>
<script>
    let prikazi = [];
    $(document).ready(function() {
        ucitajPrikaze();
        $('#forma').submit(function(e) {
            e.preventDefault();
            const cena = $('#cena').val();
            const datum = $('#datum').val();
            const film = $('#film').val();
            const sala = $('#sala').val();
            $.post('./server/index.php?akcija=prikaz.create', {
                filmId: film,
                salaId: sala,
                datum,
                cena
            }, function(res) {
                res = JSON.parse(res);
                if (!res.status) {
                    alert(res.error);
                }
                ucitajPrikaze();
            })
        })
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
        $.getJSON('./server/index.php?akcija=sala.read', function(res) {
            if (!res.status) {
                alert(res.error);
                return;
            }
            for (let sala of res.data) {
                $('#sala').append(`
                <option value='${sala.id}'>${sala.naziv}</option>
                `)
            }
        })
    })



    function ucitajPrikaze() {
        $.getJSON('./server/index.php?akcija=prikaz.read', function(res) {
            if (!res.status) {
                alert(res.error);
                return;
            }
            prikazi = res.data;
            popuniTabelu();
        });
    }

    function popuniTabelu() {
        $('#repertoar').html('');
        for (let prikaz of prikazi) {
            $('#repertoar').append(`
                <tr>
                    <td>${prikaz.film.naziv}</td>
                    <td>${prikaz.cena}</td>
                    <td>${prikaz.film.trajanje}</td>
                    <td>${prikaz.film.ocena}</td>
                    <td>${prikaz.sala.naziv}</td>
                    <td>${prikaz.datum}</td>
                    <td>
                        <button class='btn btn-danger width-100' onClick="obrisi(${prikaz.id})">Obrisi</button>    
                    </td>
                </tr>
            `)
        }
    }

    function obrisi(id) {
        $.post('./server/index.php?akcija=prikaz.delete', {
            id
        }, function(res) {
            res = JSON.parse(res);
            if (!res.status) {
                alert(res.error);
            }
            ucitajPrikaze();
        })
    }
</script>
<?php
include 'footer.php';
?>