<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateView extends Migration
{
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS rating_summary_view");

        DB::statement("
            CREATE VIEW rating_summary_view AS
            SELECT
                product_id,
                ROUND(AVG(rating), 2) AS rating,
                COUNT(*) AS rating_count
            FROM ratings
            GROUP BY product_id
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS rating_summary_view");
    }
};
