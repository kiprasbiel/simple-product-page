<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_tags', function (Blueprint $table) {
            $table->foreignId('tag_id')->after('product_id')
                ->references('id')
                ->on('tags');
            $table->unique(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_tags', function (Blueprint $table) {
            $table->dropForeign('product_tags_tag_id_foreign');
            $table->dropUnique('product_tags_product_id_tag_id_unique');
        });
    }
};
