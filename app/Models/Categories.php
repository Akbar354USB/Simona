<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Relasi: Satu kategori memiliki banyak sub-kategori
    public function sub_categories()
    {
        return $this->hasMany(SubCategories::class);
    }

    public function progress()
    {
        $totalDocs = 0;
        $uploaded = 0;

        foreach ($this->sub_categories as $sub) {
            foreach ($sub->items as $item) {

                // 1. Hitung total dokumen dari jumlah item_documents
                $totalDocs += $item->item_documents->count();

                // 2. Hitung dokumen yang sudah diupload
                foreach ($item->item_documents as $doc) {
                    if ($doc->upload()->exists()) {
                        $uploaded++;
                    }
                }
            }
        }

        return $totalDocs > 0 ? round(($uploaded / $totalDocs) * 100) : 0;
    }

    public static function overallProgress()
    {
        $totalDocs = 0;
        $uploaded = 0;

        $categories = self::with([
            'sub_categories.items.item_documents.upload'
        ])->get();

        foreach ($categories as $category) {
            foreach ($category->sub_categories as $sub) {
                foreach ($sub->items as $item) {

                    $totalDocs += $item->item_documents->count();

                    foreach ($item->item_documents as $doc) {
                        if ($doc->upload()->exists()) {
                            $uploaded++;
                        }
                    }
                }
            }
        }

        return $totalDocs > 0
            ? round(($uploaded / $totalDocs) * 100)
            : 0;
    }
}
