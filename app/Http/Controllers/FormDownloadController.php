<?php

namespace App\Http\Controllers;

use App\FormDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormDownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FormDownload::paginate();
        return view('form_download.index')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('form_download.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $input = $request->all();
        if ($request->file('pdf_src') && $request->file('pdf_src')->isValid()) {
            $input['pdf_name'] = $request->file('pdf_src')->getClientOriginalName();
            $validation = Validator::make($input, [
                'title' => 'required|unique:form_downloads,title',
                'pdf_name' => 'required_if:pdf_src,!=,null|unique:form_downloads,pdf_name',
                'doc_name' => 'unique:form_downloads,doc_name',
                'pdf_src' => 'required|max:10000|mimes:pdf',
                'doc_src' => 'nullable|max:10000|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp',
            ]);
        } elseif ($request->file('doc_src') && $request->file('doc_src')->isValid()) {
            $input['doc_name'] = $request->file('doc_src')->getClientOriginalName();
            $validation = Validator::make($input, [
                'title' => 'required|unique:form_downloads,title',
                'doc_name' => 'required_if:pdf_src,!=,null|unique:form_downloads,doc_name',
                'pdf_name' => 'unique:form_downloads,pdf_name',
                'pdf_src' => 'nullable|max:10000|mimes:pdf',
                'doc_src' => 'required|max:10000|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,txt',
            ]);
        }
        else {
            $validation = Validator::make($input, [
                'pdf_src' => 'required|max:10000|mimes:pdf',
            ]);
        }
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $input['created_by'] = Auth::id();
        $input['updated_by'] = Auth::id();

        if (isset($input['doc_src'])) {
            $docFileName = $request->file('doc_src')->getClientOriginalName();
            $input['doc_src'] = $request->file('doc_src')->storeAs('public/form_download', $docFileName);
            $input['doc_name'] = $docFileName;
        }

        if (isset($input['pdf_src'])) {
            $pdfFileName = $request->file('pdf_src')->getClientOriginalName();
            $input['pdf_src'] = $request->file('pdf_src')->storeAs('public/form_download', $pdfFileName);
            $input['pdf_name'] = $pdfFileName;
        }

        FormDownload::create($input);
        return redirect('/form_downloads')->with(['success' => 1]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function showFile($name) {
        return response()->file(storage_path('app/public/form_download/'.$name));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:form_downloads,id',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        if (FormDownload::destroy($id)) {
            return redirect('/form_downloads')->with(['success' => 1]);
        }
    }
}
