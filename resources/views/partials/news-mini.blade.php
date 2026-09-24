        <a class="nwp-mini" href="{{ $post->url() }}">
          <span class="nwp-mini__thumb"><img src="{{ media($post->cover_image) }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>{{ $post->title }}</strong>
            <em>{{ $post->dateLabel() }}</em>
          </span>
        </a>
