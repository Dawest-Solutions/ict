<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RewardCreateRequest;
use App\Http\Requests\RewardUpdateRequest;
use App\Models\Reward;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class RewardController extends Controller
{
    const IMAGE_DIRECTORY_BASE = 'app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'rewards';
    const IMAGE_DIRECTORY = 'app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'rewards' . DIRECTORY_SEPARATOR;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $items = Reward::all();

        return view('admin.rewards.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.rewards.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RewardCreateRequest  $request
     * @return Application|Factory|View
     */
    public function store(RewardCreateRequest $request)
    {
        $data = $request->validated();

        try {
            $data['image_path'] = $this->saveImage($request->file('image'));
            unset($data['image']);
            Reward::create($data);

            return $this->index()->with('success', collect(['A new reward has been created.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while creating the new reward: ' . $e->getMessage()]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Reward $reward
     * @return Application|Factory|View
     */
    public function show(Reward $reward)
    {
        return view('admin.rewards.details', compact('reward'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reward $reward
     * @return Application|Factory|View
     */
    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RewardUpdateRequest $request
     * @param Reward $reward
     * @return Application|Factory|View
     */
    public function update(RewardUpdateRequest $request, Reward $reward)
    {
        $data = $request->validated();
        $data['image_path'] = $this->saveImage($request->file('image'));
        unset($data['image']);

        try {
            Reward::find($reward->id)->update($data);

            return $this->index()->with('success', collect(['Reward has been updated.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while updating the new reward: ' . $e->getMessage()]));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reward $reward
     * @return Application|Factory|View
     */
    public function destroy(Reward $reward)
    {
        try {
            Reward::find($reward->id)->delete();

            return $this->index()->with('success', collect(['Reward has been deleted.']));
        } catch (\Exception $e) {
            return $this->index()->with('errors', collect(['There was an error while creating the new reward: ' . $e->getMessage()]));
        }
    }

    private function saveImage($image): string
    {
        // set image names
        $imageName = date("YmdHis");
        $imageOriginalName = $imageName . '.' . $image->getClientOriginalExtension();
        $imagePreviewName = 'lg_' . $imageName . '.' . $image->getClientOriginalExtension();
        $imageThumbName = 'sm_' . $imageName . '.' . $image->getClientOriginalExtension();

        // save orginal image
        $image->move(storage_path(self::IMAGE_DIRECTORY_BASE), $imageOriginalName);

        // create image
        $image = Image::make(storage_path(self::IMAGE_DIRECTORY . $imageOriginalName));

        // create and save preview
        if( $image->width() > $image->height() ){
            $image->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $image->resize(null, 1000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $image->save(storage_path(self::IMAGE_DIRECTORY . $imagePreviewName));

        // create and save thumb
        $image->fit(150, 100);
        $image->save(storage_path(self::IMAGE_DIRECTORY . $imageThumbName));

        return $imageOriginalName;

    }
}
