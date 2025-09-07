<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // ファイルをアップロードして保存する処理
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'display_name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // PDFと画像のみ、10MBまで
        ]);

        $file = $request->file('document_file');
        $path = $file->store('documents', 'public'); // publicディスクのdocumentsフォルダに保存

        Document::create([
            'customer_id' => $request->customer_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'display_name' => $request->display_name,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', '書類をアップロードしました。');
    }

    // ファイルをダウンロードさせる処理
    public function download(Document $document)
    {
        // public/storage/documents/xxxx.pdf のような物理的なパスを取得
        $pathToFile = storage_path('app/public/' . $document->file_path);

        // ファイル名に拡張子を追加
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
        $filename = $document->display_name . '.' . $extension;

        // response()ヘルパーを使ってダウンロードさせる
        return response()->download($pathToFile, $filename);
    }

    // ファイルを削除する処理
    public function destroy(Document $document)
    {
        // 権限チェック（例：アップロードした本人のみ削除可能）
        if (Auth::id() !== $document->user_id) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', '書類を削除しました。');
    }

    public function preview(Document $document)
    {
        $pathToFile = storage_path('app/public/' . $document->file_path);

        // This helper automatically sets the correct headers for in-browser viewing
        return response()->file($pathToFile);
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'memo' => 'nullable|string',
        ]);

        $document->update([
            'display_name' => $request->display_name,
            'memo' => $request->memo,
        ]);

        return redirect()->route('customers.show', $document->customer_id)->with('success', '書類情報を更新しました。');
    }
}
