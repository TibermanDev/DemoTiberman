<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/** Slug mengikuti blog lama di tiberman.com/blog/ supaya URL artikel tetap sama. */
class BlogController extends Controller
{
    /** Kartu per section kategori. */
    private const PER_SECTION = 3;

    public function index(?string $category = null): View
    {
        $categories = PostCategory::query()->ordered()->get();

        if ($category !== null) {
            abort_unless($categories->contains('slug', $category), 404);
        }

        $posts = Post::query()->live()->with('category')->get();

        // Sorotan = artikel unggulan terbaru; sisanya yang terbaru di samping.
        $feature = $posts->firstWhere('is_featured', true) ?? $posts->first();
        $latest = $posts->reject(fn (Post $p) => $p->is($feature))->take(4);

        $sections = $categories->map(function (PostCategory $c) use ($posts) {
            $items = $posts->where('post_category_id', $c->id)->values();

            return [
                'category' => $c,
                // headline kategori: unggulan di kategori itu, kalau tidak ada yang terbaru
                'hero' => $items->firstWhere('is_featured', true) ?? $items->first(),
                'posts' => $items->take(self::PER_SECTION),
            ];
        })->filter(fn (array $s) => $s['posts']->isNotEmpty());

        return view('news', [
            'category' => $category ?? 'all',
            'categories' => $categories,
            'feature' => $feature,
            'latest' => $latest,
            'sections' => $sections,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->live()->where('slug', $slug)->with(['category', 'tags'])->firstOrFail();

        $others = Post::query()->live()->whereKeyNot($post->id);

        return view('news-detail', [
            'post' => $post,
            'categories' => PostCategory::query()->ordered()->get(),
            'popular' => $this->popular($others->clone()),
            'related' => $others->clone()->where('post_category_id', $post->post_category_id)->take(3)->get()
                ->whenEmpty(fn () => $others->clone()->take(3)->get()),
        ]);
    }

    private function popular($query): Collection
    {
        $popular = $query->clone()->where('is_popular', true)->take(5)->get();

        return $popular->isNotEmpty() ? $popular : $query->take(5)->get();
    }
}
