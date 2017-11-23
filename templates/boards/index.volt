
{{ partial('partials/header') }}

<div class="container">
    <main role="main">
        <div class="row">
        {% for board in boards %}
            <div class="col-md-3">
                <div class="card">
                  <a href="chess.php?_url=/boards/{{ board.id }}"><img class="card-img-top" src="img/sample.png" alt="{{ board.al_user_white.name }} vs. {{ board.al_user_black.name }}"></a>
                  <div class="card-body">
                    <h4 class="card-title">{{ board.al_user_white.name }} vs. {{ board.al_user_black.name }}</h4>
                    <p class="card-text">35 sn</p>
                    <a href="chess.php?_url=/boards/{{ board.id }}" class="btn btn-primary">Maça Git</a>
                  </div>
                </div>
            </div>
        {% endfor %}
        </div>
    </main>
</div>
{{ partial('partials/footer') }}