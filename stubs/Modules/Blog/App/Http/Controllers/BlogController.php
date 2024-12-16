<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Label;
use Inertia\Inertia;
use function App\Http\Controllers\abort_if;
use function App\Http\Controllers\request;

class BlogController extends Controller
{
    public function index()
    {
        $page = Label::getModel('blog-overview');

        $pageNumber = request()->input('page', 1);
        $per_page = request()->input('per_page', 3);
        $blogs = Blog::query()->visible()->published()->paginate(
            page: $pageNumber,
            perPage: $per_page
        );

        return Inertia::render(
            'resources/page/blog-overview',
            [
                'page' => $page,
                'blogs' => Inertia::merge($blogs)
            ]
        );
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
            );
        }

        if (file_exists(resource_path('js/Pages/resources/blog/default.jsx'))) {
            return Inertia::render(
                'resources/blog/default',
                [
                    'blog' => $blog,
                ]
            );
        }

        return Inertia::render(
            'resources/index',
            [
                'model' => $blog,
            ]
        );
    }
}
