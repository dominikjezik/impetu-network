<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\SubPage;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubPageMediaController extends Controller
{
    private MediaService $mediaService;

    /**
     * SubPageMediaController constructor.
     *
     * @param MediaService $mediaService
     */
    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * Store a newly created media in database
     * and store photo & banner in file storage.
     *
     * @param Request $request
     * @param SubPage $subPage
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request, SubPage $subPage)
    {
        $request->validate([
            'photo' => ['nullable', 'base64image', 'base64mimes:jpg,jpeg,png', 'base64dimensions:min_width=100,min_height=100', 'base64max:7000'],
            'banner' => ['nullable' ,'image', 'mimes:jpeg,png,jpg', 'max:7000']
        ]);

        if(!is_null($request->get('photo'))) {
            if(!is_null($subPage->photo->first())) {
                $media = $subPage->photo->first();
                $this->mediaService->deleteMedia($media);
            }

            $file = $this->mediaService->saveTemporarily($request->get('photo'));
            $path = $this->mediaService->storeFile($file);
            $subPage->attachNewPhoto($path);
        }

        if(!is_null($request->file('banner'))) {
            if(!is_null($subPage->banner->first())) {
                $media = $subPage->banner->first();
                $this->mediaService->deleteMedia($media);
            }

            $path = $this->mediaService->storeFile($request->file('banner'));
            $subPage->attachNewBanner($path);
        }

        return redirect()->route('subpages.show', $subPage)
            ->with("message", "Photo or banner has been updated!");
    }


    /**
     * Remove the specified photo from database and file storage.
     *
     * @param SubPage $subPage
     * @return RedirectResponse
     */
    public function destroyPhoto(SubPage $subPage)
    {
        $media = $subPage->photo->first();
        abort_if(is_null($media), 404);

        $this->mediaService->deleteMedia($media);
        return redirect()->route('subpages.show', $subPage)
            ->with("message", "Photo deleted successfully!");
    }

    /**
     * Remove the specified banner from database and file storage.
     *
     * @param SubPage $subPage
     * @return RedirectResponse
     */
    public function destroyBanner(SubPage $subPage)
    {
        $media = $subPage->banner->first();
        abort_if(is_null($media), 404);

        $this->mediaService->deleteMedia($media);
        return redirect()->route('subpages.show', $subPage)
            ->with("message", "Banner deleted successfully!");
    }
}
