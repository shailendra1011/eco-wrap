<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $total_faqs   =   Faq::count();

        return view('admin.faq.faq_list', ['total_faqs' => $total_faqs]);
    }


    public function faqList(Request $request)
    {
        $search = $request->search;
        $queries = Faq::where(function ($query) use ($search) {
            $query->where('question', 'LIKE', '%' . $search . '%');
            $query->orWhere('answer', 'LIKE', '%' . $search . '%');
        });
        if ($request->from_date) {
            $queries = $queries->whereDate('created_at', '>=', Carbon::parse($request->from_date));
        }
        if ($request->to_date) {
            $queries = $queries->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }
        
        $queries = $queries->orderBy('id', 'DESC')->paginate(10);
       return ['data' => $queries];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages  =   \App\Language::select('language','language_code')->distinct('language')->get();

        return view('admin.faq.add_faq_form',['languages' => $languages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'language'      =>  'required',
            'questions'     =>  'required|array',
            'answers'       =>  'required|array'
        ]);

        try {

            $insertArray    =   array();

            foreach($request->questions as $key => $value){
                $tempArray  =   array();

                $tempArray['language']      =   $request->language;
                $tempArray['question']      =   trim($value);
                $tempArray['answer']        =   trim($request->answers[$key]);
                $tempArray['created_at']    =   date('Y-m-d H:i:s');

                array_push($insertArray,$tempArray);
            }

            Faq::insert($insertArray);

            return back()->with('success','Data added successfully.');

        } catch(\Exception $e) {

            \Log::error($e->getMessage());

            return back()->with('error','Failed to add FAQ.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
