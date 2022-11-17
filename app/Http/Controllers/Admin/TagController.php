<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateTag($request);
        $inputTag = $request->all();
        $newTag = new Tag();
        $newTag->fill($inputTag);
        $slug = Str::slug($newTag->name);
        $slug_base = $slug;
        $existingslug = Post::where('slug', $slug)->first();
        $counter = 1;
        while ($existingslug) {
            $slug = $slug_base . '_' . $counter;
            $existingslug = Post::where('slug', $slug)->first();
            $counter++;
        }
        $newTag->slug = $slug;
        return redirect()->route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $posts = Post::all();
        $postsRelated = [];
        foreach ($posts as $post) {
            if ($post->tags->contains($tag))
                $postsRelated[] = $post;
        }
        return view('admin.tags.show', compact(['tag', 'postsRelated']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $this->validateTag($request);
        $inputTag = $request->all();
        $tag->update($inputTag);
        $slug = Str::slug($tag->name);
        $slug_base = $slug;
        $existingslug = Post::where('slug', $slug)->first();
        $counter = 1;
        while ($existingslug) {
            $slug = $slug_base . '_' . $counter;
            $existingslug = Post::where('slug', $slug)->first();
            $counter++;
        }
        $tag->slug = $slug;
        $tag->save();
        return redirect()->route('admin.tags.show', $tag->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag, Post $post)
    {
        $tag->posts()->sync([]);
        $tag->delete();
        return redirect()->route('admin.tags.index');
    }

    public function validateTag(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|',
        ]);
    }
}
