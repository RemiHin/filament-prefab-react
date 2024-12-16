<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Label;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $page = Label::getModel('blog-overview');

        $blogs = Blog::query()->visible()->published()->paginate(3);

        return Inertia::render(
            'resources/page/blog-overview',
            [
                'page' => $page,
                'blogs' => $blogs
            ]
        )
            ->withViewData([
                'seo' => $page->seo,
            ]);
    }

    public function show(Blog $blog): \Inertia\Response
    {
        return $this->getComponent($blog);
    }

    protected function getComponent(Blog $blog): \Inertia\Response
    {
        abort_if(!$blog->isVisible(), 404);

        if ($blog->label?->label && file_exists(resource_path('js/Pages/resources/blog/') . $blog->label->label . '.jsx')) {
            return Inertia::render(
                'resources/blog/' . strtolower($blog->label->label),
                [
                    'blog' => $blog,
                ]
            )
                ->withViewData([
                    'seo' => $blog->seo,
                ]);
        }

        if (file_exists(resource_path('js/Pages/resources/blog/default.jsx'))) {
            return Inertia::render(
                'resources/blog/default',
                [
                    'blog' => $blog,
                ]
            )
                ->withViewData([
                    'seo' => $blog->seo,
                ]);
        }

        return Inertia::render(
            'resources/index',
            [
                'model' => $blog,
            ]
        )
            ->withViewData([
                'seo' => $blog->seo,
            ]);
    }
}
