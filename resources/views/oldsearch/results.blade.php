<div  v-for="item in results">
        <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
          <header>

            <img class="cover" width="155" v-bind:src="show.img" alt="">
          
          </header>
          <div class="mdl-card mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__supporting-text">
              <h4> <a target="_blank" class="mdl-button" href="@{{item.location}}">@{{{item.title}}}</a></h4>

              <p class="uk-text-justify"><strong>
              @{{{item.description}}}</strong> posted by @{{item.owner}} @{{item.date}} days ago </p>
              <p>@{{{item.text}}}</p>
               <div id="@{{{show.id}}}" data-rating="@{{{show.rank}}}" class="starrr" >@{{{show.rank}}
            <span id="count-existing">@{{{item.rank}}}</span></div>
              
              
              <div class="mdl-card__actions">
                <a target="_blank" class="mdl-button" href="@{{item.location}}">Read Document</a>
               
                <a href="/documents/@{{item.id}}/add" ><i class="far fa-bookmark" aria-hidden="true"> </i> 
                Add to my library</a>

              </div>

            </div>

          </div>
        </section>
    <br>


</div>
</div>