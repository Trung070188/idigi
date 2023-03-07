<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use Illuminate\Http\Request;

class FilesController extends AdminBaseController
{
    public function index()
    {
        return view('admin.files.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->get('keyword');
        $sortDirection = $request->get('sort_direction') === 'asc' ? 'asc' : 'desc';
        $sortField = $request->get('sort_field');
       // $fileType = $request->get('file_type');

        $allowedSortFiled = [
            'id' => true,
            'name' => true,
            'extension' => true,
            'size' => true,
            'created_at' => true
        ];

        if (!isset($allowedSortFiled[$sortField])) {
            $sortField = 'id';
        }

        $query = File::query()
            ->orderBy($sortField, $sortDirection);

        if ($keyword) {
            $query->where('name', 'LIKE', '%'.$keyword.'%');
        }

        /*if ($fileType === 'image') {
            $query->where('is_image', 1);
        } elseif ($fileType === 'excel') {
            $query->where('extension', 'xlsx');
        }*/

        $query->createdIn($request->get('created'));

        $files = $query->paginate(15);

        return [
            'code' => 200,
            'data' => $files->items(),
            'paginate' => [
                'total' => $files->total(),
                'currentPage' => $files->currentPage(),
                'lastPage' => $files->lastPage(),
            ]
        ];
    }

    /**
     * error: 0
     * name: "10951873_433905603431654_87195138_n.jpg"
     * size: 55820
     * tmp_name: "C:\xampp74\tmp\php9E00.tmp"
     * type: "image/jpeg".
     *
     * @return array
     */
    public function upload()
    {
        if (empty($_FILES['file_0'])) {
            return [
                'code' => 1,
                'message' => 'File Missing'
            ];
        }

        $file0 = $_FILES['file_0'];

        if ($file0['error']) {
            return [
                'code' => 1,
                'message' => 'File Error Code: '.$file0['error']
            ];
        }

        $y = date('Y');
        $m = date('m');

        $dir = public_path("files/attachments/{$y}/{$m}");

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $allowed = [
            'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'png'
        ];

        $imageExtension = [
            'jpg' => true, 'jpeg' => true, 'png' => true
        ];

        $info = pathinfo($file0['name']);
        if(@$info['extension'])
        {
            $extension = strtolower($info['extension']);
            $hash = sha1(uniqid());
            $newFilePath = $dir.'/'.$hash.'.'.$extension;

            $newUrl = url("/files/attachments/{$y}/{$m}/{$hash}.{$extension}");

            if (!in_array($extension, $allowed)) {
                return [
                    'code' => 3,
                    'message' => 'Extension: '.$extension.' is now allowed'
                ];
            }


        }
        else{
            $hash = sha1(uniqid());
            $newFilePath = $dir.'/'.$hash;

            $newUrl = url("/files/attachments/{$y}/{$m}/{$hash}");

        }



        $ok = move_uploaded_file($file0['tmp_name'], $newFilePath);

        if (!$ok) {
            return [
                'code' => 4,
                'message' => 'Move uploaded failed'
            ];
        }

        $user = auth_user();
        $file = new File();
        $file->type = $file0['type'];
        $file->hash = sha1($newFilePath);
        $file->url = $newUrl;
        $file->is_image = @isset($imageExtension[$extension]) ? 1 : 0;
        $file->size = $file0['size'];
        $file->name = $info['filename'];
        $file->path = $newFilePath;
        $file->uploaded_by = $user->name;
        $file->extension = @$extension;
        $file->save();



        return [
            'code' => 200,
            'file' => $file,
            'message' => 'Upload thành công'
        ];
    }

    public function rename(Request $request)
    {
        $id = $request->get('id');
        $newName = trim($request->get('new_name'));

        if (!$newName) {
            return [
                'code' => 404,
                'message' => 'Vui lòng nhập tên file mới'
            ];
        }

        $file = File::where('id', $id)->first();

        if (!$file) {
            return [
                'code' => 404,
                'message' => 'File not found'
            ];
        }

        $file->name = $newName;
        $file->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
    }

    public function remove(Request $request)
    {
        $id = $request->get('id');
        $file = File::where('id', $id)->first();

        if (!$file) {
            return [
                'code' => 404,
                'message' => 'File not found'
            ];
        }

        try {
            if (is_file($file->path)) {
                @unlink($file->path);
            }

            $file->delete();

            return [
                'code' => 200,
                'message' => 'Đã xóa file'
            ];
        } catch (\Exception $e) {
            return [
                'code' => 503,
                'message' => $e->getMessage()
            ];
        }
    }
}
