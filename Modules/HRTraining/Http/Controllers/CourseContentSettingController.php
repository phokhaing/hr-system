<?php


namespace Modules\HRTraining\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\HRTraining\Entities\CourseContents;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Http\Requests\StoreCourseContentSettingRequest;

class CourseContentSettingController extends Controller
{
    public function index()
    {
        $courses = Courses::with('contents')
            ->getCourseByUser()
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();
        return view('hrtraining::course_content_setting.index')->with(compact('courses'));
    }

    public function edit($id)
    {
        $course = Courses::with('content')->find($id);
        return view('hrtraining::course_content_setting.edit')->with(compact('course'));
    }

    public function store(StoreCourseContentSettingRequest $request)
    {
        $section = [
            'course_id' => $request->course_id,
            'type' => $request->content_type,
            'json_data->title' => $request->section_title,
            'json_data->description' => $request->section_desc,
            'json_data->content_type' => $request->content_type
        ];

        switch ($request->content_type) {
            case COURSE_CONTENT_TYPE['LINK']:
                $section['json_data->link'] = $request->section_link;
                break;

            case COURSE_CONTENT_TYPE['PLAIN_TEXT']:
                $section['json_data->plain_text'] = $request->section_content;
                break;

            case COURSE_CONTENT_TYPE['SOUND']:
                $course = Courses::courseObject($request->course_id);
                $directory = courseContentDirectory($request->course_id, @to_object($course->json_data)->title, SOUND_PREFIX_PATH);
                $path = saveFile($request->file('section_audio'), $directory);
                $section['json_data->sound_path'] = $path;
                break;

            case COURSE_CONTENT_TYPE['IMAGE']:
                $course = Courses::courseObject($request->course_id);
                $directory = courseContentDirectory($request->course_id, @to_object($course->json_data)->title, IMAGE_PREFIX_PATH);
                $path = saveImageFile($request->section_photo, $directory);
                $section['json_data->image_path'] = $path;
                break;

            case COURSE_CONTENT_TYPE['PDF']:
                $course = Courses::courseObject($request->course_id);
                $directory = courseContentDirectory($request->course_id, @to_object($course->json_data)->title, PDF_PREFIX_PATH);
                $path = saveFile($request->section_pdf, $directory);
                $section['json_data->pdf_path'] = $path;
                break;

            default:
                break;
        }

        $course = new CourseContents();
        $course->createRecord($section);

        return redirect()
            ->back()
            ->with(['success' => 1]);
    }

    public function update(StoreCourseContentSettingRequest $request)
    {
        $section = [
            'json_data->title' => $request->section_title,
            'json_data->description' => $request->section_desc
        ];

        switch ($request->content_type) {
            case COURSE_CONTENT_TYPE['LINK']:
                $section['json_data->link'] = $request->section_link;
                break;

            case COURSE_CONTENT_TYPE['PLAIN_TEXT']:
                $section['json_data->plain_text'] = $request->section_content;
                break;

            case COURSE_CONTENT_TYPE['SOUND']:

                $updateFile = $request->file('section_audio');
                if ($updateFile) {
                    //Delete current sound file
                    $content = CourseContents::find($request->content_id);
                    Storage::delete(to_object($content->json_data)->sound_path);

                    //Create new sound file
                    $course = Courses::courseObject($request->course_id);
                    $directory = courseContentDirectory($request->course_id, @to_object($course->json_data)->title, SOUND_PREFIX_PATH);
                    $path = saveFile($updateFile, $directory);
                    $section['json_data->sound_path'] = $path;
                }

                break;

            case COURSE_CONTENT_TYPE['IMAGE']:

                $updateFile = $request->section_photo;
                if ($updateFile) {
                    //Delete current file
                    $content = CourseContents::find($request->content_id);
                    Storage::delete(to_object($content->json_data)->image_path);

                    //Create new file
                    $course = Courses::courseObject($request->course_id);
                    $directory = courseContentDirectory($request->course_id, @to_object($course->json_data)->title, IMAGE_PREFIX_PATH);
                    $path = saveImageFile($updateFile, $directory);
                    $section['json_data->image_path'] = $path;
                }

                break;

            case COURSE_CONTENT_TYPE['PDF']:
                if ($request->section_pdf) {
                    $course = Courses::courseObject($request->course_id);
                    $directory = courseContentDirectory($request->course_id, @$request->course_id, PDF_PREFIX_PATH);
                    $path = saveFile($request->section_pdf, $directory);
                  
                    $section['json_data->pdf_path'] = @$path;
                }

                break;

            default:
                break;
        }

        $course = new CourseContents();
        $updated = $course->updateRecord($request->content_id, $section);

        if ($updated) {
            return redirect()
                ->back()
                ->with(['success' => 1]);
        }

        return redirect()
            ->back()
            ->with(['success' => 0]);
    }

    public function delete($content_id)
    {
        $course = CourseContents::find($content_id);
        if ($course) {
            $contentObj = @to_object($course->json_data);
            switch ($course->type) {
                case COURSE_CONTENT_TYPE['SOUND']:
                    Storage::delete($contentObj->sound_path);
                    break;

                case COURSE_CONTENT_TYPE['IMAGE']:
                    Storage::delete($contentObj->image_path);
                    break;

                default:
                    break;
            }
            $course->delete();

            return redirect()
                ->back()
                ->with(['success' => 1]);
        }

        return redirect()
            ->back()
            ->with(['success' => 0]);
    }
}
