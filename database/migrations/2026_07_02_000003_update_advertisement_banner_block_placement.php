<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertisementBannerBlockPlacement extends Migration
{
    public function up()
    {
        $this->updateTemplateTable('core_templates');
        $this->updateTemplateTable('core_template_translations');
    }

    public function down()
    {
        //
    }

    protected function updateTemplateTable($table)
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        DB::table($table)
            ->where('content', 'like', '%"type":"advertisement_banner"%')
            ->orderBy('id')
            ->get(['id', 'content'])
            ->each(function ($row) use ($table) {
                $content = json_decode($row->content, true);

                if (!is_array($content)) {
                    return;
                }

                $changed = $this->updateBlocks($content);

                if (!$changed) {
                    return;
                }

                DB::table($table)
                    ->where('id', $row->id)
                    ->update([
                        'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
                        'updated_at' => now(),
                    ]);
            });
    }

    protected function updateBlocks(&$blocks)
    {
        $changed = false;

        foreach ($blocks as &$block) {
            if (($block['type'] ?? null) === 'advertisement_banner') {
                $currentPlacement = $block['model']['placement'] ?? null;

                if (!$currentPlacement || $currentPlacement === 'large_banner') {
                    $block['model']['placement'] = 'advertisement_banner';
                    $changed = true;
                }
            }

            foreach (['children', 'items', 'columns'] as $childKey) {
                if (!empty($block[$childKey]) && is_array($block[$childKey])) {
                    $changed = $this->updateBlocks($block[$childKey]) || $changed;
                }
            }
        }

        return $changed;
    }
}
