<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatepostRequest;
use App\Http\Requests\UpdatepostRequest;
use App\Repositories\postRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\metaSeo;
use App\Models\post;
use Illuminate\Support\Facades\Auth;


class postController extends AppBaseController
{
    /** @var  postRepository */
    private $postRepository;

    public function __construct(postRepository $postRepo)
    {
        $this->postRepository = $postRepo;
    }

    /**
     * Display a listing of the post.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $posts = post::Orderby('id', 'desc')->paginate(10);
        
       
        return view('posts.index')
            ->with('posts', $posts);
    }

    /**
     * Show the form for creating a new post.
     *
     * @return Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     *
     * @param CreatepostRequest $request
     *
     * @return Response
     */
    public function store(CreatepostRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('image')) {

            $file_upload = $request->file('image');

            $name = time() . '_' . $file_upload->getClientOriginalName();

            $filePath = $file_upload->storeAs('uploads', $name, 'public');

            $input['image'] = $filePath;
        }
        $input['link'] = $this->createSlug($input['title']);


      
        $input['id_user'] = Auth::id();

        $meta_model = new metaSeo();


        $meta_model->meta_title = $input['title'];

        $meta_model->meta_content = $input['shortcontent'];

        $meta_model->meta_og_content = $input['title'];

        $meta_model->meta_key_words = $input['title'];

        $meta_model->meta_og_title = $input['shortcontent'];

        $meta_model->save();

        $input['Meta_id'] = $meta_model['id'];



        $post = $this->postRepository->create($input);

        Flash::success('Post saved successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified post.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified post in storage.
     *
     * @param int $id
     * @param UpdatepostRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatepostRequest $request)
    {
        $post = $this->postRepository->find($id);

       
        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $input = $request->all();

        if ($request->hasFile('image')) {

            $file_upload = $request->file('image');

            $name = time() . '_' . $file_upload->getClientOriginalName();

            $filePath = $file_upload->storeAs('uploads', $name, 'public');

            $input['image'] = $filePath;
        }
        $input['link'] = $this->createSlug($input['title']);

      

        $input['id_user'] = Auth::id();



        $post = $this->postRepository->update($input, $id);

        Flash::success('Post updated successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified post from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $this->postRepository->delete($id);

        Flash::success('Post deleted successfully.');

        return redirect(route('posts.index'));
    }

    public function createSlug($str, $delimiter = '-'){
        $str  = $this->convert_vi_to_en($str); 
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;

    } 

    public function addActive(Request $request)
    {
       $id = $request->id;

       $active = $request->active;

       $post = post::find($id);

       if($active ==0){
            $post->active =1;
            $post->save();

       } 
       else{

         $post->active =0;
         $post->save();
       }
    }

    public function addHightLight(Request $request)
    {
       $id = $request->id;

       $active = $request->active;

       $post = post::find($id);

      



       if($active == 1){
            $post->hight_light =0;
            $post->save();

       } 
       else{

         $post->hight_light =1;
          $post->save();
       }
    }


    public function convert_vi_to_en($str) {
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'a', $str);
          $str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'e', $str);
          $str = preg_replace("/(??|??|???|???|??)/", 'i', $str);
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'o', $str);
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'u', $str);
          $str = preg_replace("/(???|??|???|???|???)/", 'y', $str);
          $str = preg_replace("/(??)/", 'd', $str);
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'A', $str);
          $str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'E', $str);
          $str = preg_replace("/(??|??|???|???|??)/", 'I', $str);
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'O', $str);
          $str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'U', $str);
          $str = preg_replace("/(???|??|???|???|???)/", 'Y', $str);
          $str = preg_replace("/(??)/", 'D', $str);
          //$str = str_replace(" ???, ???-", str_replace(???&*#39;???,???",$str));
          return $str;
    }
}
