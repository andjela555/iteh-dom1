<?php
include 'header.php';
?>

<div class='container mt-5 bg-white pb-5 border rounded transparent'>
    <h1 class="text-center">Spisak filmova</h1>
    <div class='row mt-4'>
        <div class='col-8'>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naziv</th>
                        <th>Trajanje</th>
                        <th>Ocena</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody id='filmovi'>

                </tbody>
            </table>
        </div>
        <div class='col-4'>
            <h3>Forma film</h3>
            <form id='forma'>
                <label>Naziv</label>
                <input class="form-control" type="text" id='naziv'>
                <label>Trajanje</label>
                <input class="form-control" type="number" min='0' id='trajanje'>
                <label>Ocena</label>
                <input class="form-control" type="number" min='0' max='10' id='ocena'>
                <button id='sacuvaj' class="btn btn-primary form-control mt-2">Kreiraj</button>
            </form>
            <button id='obrisi' class="btn btn-danger mt-2 form-control" hidden>Obrisi</button>
            <button id='nazad' class="btn btn-secondary mt-2 form-control" hidden>Nazad</button>
        </div>
    </div>
</div>
<script>
    let filmovi = [];
    let selId = 0;
    $(document).ready(function() {
        ucitajFilmove();
        $('#nazad').click(function() {
            otvori(0);
        })
        $("#obrisi").click(function() {
            obrisiFilm();
            otvori(0);
        })
        $('#forma').submit(function(e) {
            e.preventDefault();
            const naziv = $('#naziv').val();
            const trajanje = $('#trajanje').val();
            const ocena = $('#ocena').val();
            $.post('./server/index.php?akcija=film.' + (selId ? 'update' : 'create'), {
                naziv,
                ocena,
                trajanje,
                id: selId
            }, function(res) {
                res = JSON.parse(res);
                if (!res.status) {
                    alert(res.error);
                }
                ucitajFilmove();
                otvori(0);
            })
        })
    })

    function obrisiFilm() {
        $.post('./server/index.php?akcija=film.delete', {
            id: selId
        }, function(res) {
            res = JSON.parse(res);
            if (!res.status) {
                alert(res.error);
            }
            ucitajFilmove();
        })
    }

    function ucitajFilmove() {
        $.getJSON('./server/index.php?akcija=film.read', function(res) {
            if (!res.status) {
                alert(res.error);
                return;
            }
            $('#filmovi').html('');
            filmovi = res.data;
            for (let film of filmovi) {
                $('#filmovi').append(`
                    <tr>
                        <td>${film.id}</td>
                        <td>${film.naziv}</td>
                        <td>${film.trajanje}</td>
                        <td>${film.ocena}</td>
                        <td>
                            <button onClick="otvori(${film.id})" class='btn btn-secondary width-100'>Detalji</button>
                        </td>
                    </tr>
                
                `)
            }
        })
    }

    function otvori(id) {
        selId = id;
        const film = filmovi.find(e => e.id == id);
        if (!film) {
            $('#naziv').val('');
            $('#trajanje').val('');
            $('#ocena').val('');
            $('#nazad').attr('hidden', true);
            $('#obrisi').attr('hidden', true);
            $('#sacuvaj').html('Kreiraj');
        } else {
            $('#naziv').val(film.naziv);
            $('#trajanje').val(film.trajanje);
            $('#ocena').val(film.ocena);
            $('#nazad').attr('hidden', false);
            $('#obrisi').attr('hidden', false);
            $('#sacuvaj').html('Izmeni');
        }
    }
</script>
<?php
include 'footer.php';
?>