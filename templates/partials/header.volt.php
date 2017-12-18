<!doctype html>
<html lang="en">
<head>
    <title>Sign In</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <style>
        body {
            padding-bottom: 20px;
        }

        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <?= $this->partial('partials/navigation') ?>
</div>

<!-- Modal -->
<div class="modal fade" id="createBoard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="chess.php?_url=/create" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yeni Masa Oluştur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="board_name">Masa Adı</label>
                        <input type="text" class="form-control" name="board_name" id="board_name" placeholder="<?= $this->session->get('auth')['name'] ?> vs. ">
                    </div>
                    <div class="form-group">
                        <label>Takım</label>

                        <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="color" value="b"> Siyah
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="color" value="w"> Beyaz
                            </label>
                        </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Vazgeç</button>
                <button type="submit" class="btn btn-primary">Oluştur</button>
            </div>

            </form>
        </div>
    </div>
</div>