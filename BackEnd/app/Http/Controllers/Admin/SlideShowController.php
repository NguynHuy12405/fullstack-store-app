<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideShowController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('sort_order')->paginate(3);
        return view('admin.slideshow.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.slideshow.create');
    }

    /**
     * STORE
     */
    public function store(StoreSlideRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();

        // nếu có lưu file thật
        $file->storeAs('', $fileName, 'public');

        $data['image'] = $fileName;

        Slide::create($data);

        return redirect()
            ->route('admin.slideshow.index')
            ->with('success', 'Thêm slide thành công');
    }

    public function edit(Slide $slide)
    {
        return view('admin.slideshow.edit', compact('slide'));
    }

    /**
     * UPDATE
     */
    public function update(UpdateSlideRequest $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('', $fileName, 'public');

            $data['image'] = $fileName;
        }   

        $slide->update($data);

        return back()->with('success', 'Cập nhật slide thành công!');
    }

    /**
     * DESTROY
     */
    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);

        $slide->delete();

        return back()->with('success', 'Đã xóa slide thành công!');
    }
}
