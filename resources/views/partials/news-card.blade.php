        <a class="news-card" href="{{ $post->url() }}">
          <div class="news-card__thumb"><img src="{{ media($post->cover_image) }}" alt="{{ $post->cover_alt }}" loading="lazy"></div>
          <span class="news-card__date">{{ $post->dateLabel() }}</span>
          <h3>{{ $post->title }}</h3>
          <p>{{ $post->excerpt }}</p>
        </a>
