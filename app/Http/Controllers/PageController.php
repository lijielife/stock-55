<?php

class PageController extends BaseController {

    // Add methods to add, edit, delete and show pages

    // create method to create new pages
    // submit the form to this method
    public function create()
    {
        $inputs = Input::all();
        $page = Page::create(array(...));
    }

    // Show a page by slug
    public function show($slug = 'home')
    {
        $page = page::whereSlug('admins.'$slug)->get();
        return View::make('pages.index')->with('page', $page);
    }
}