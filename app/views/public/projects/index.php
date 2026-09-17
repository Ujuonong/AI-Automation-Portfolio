<?php
/**
 * Projects listing with search + filters.
 * Vars: $projects, $pagination, $search, $type, $status, $tech_id, $types, $statuses, $all_technologies, $technologies
 */
?>
<section class="section section--tight">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow"><?= e(content('projects_eyebrow')) ?></span>
            <h1><?= e(content('projects_heading')) ?></h1>
            <p class="lead"><?= e(content('projects_intro')) ?></p>
        </div>

        <form class="filter-bar" method="get" action="<?= e(url('/projects')) ?>">
            <input class="input" type="search" name="q" value="<?= e($search) ?>" placeholder="<?= e(content('projects_search_placeholder')) ?>" aria-label="<?= e(content('projects_search_placeholder')) ?>">
            <select class="select" name="type" aria-label="Filter by type">
                <option value=""><?= e(content('projects_filter_types')) ?></option>
                <?php foreach ($types as $t): ?>
                <option value="<?= e($t) ?>" <?= $type === $t ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($t, '_'))) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="select" name="status" aria-label="Filter by status">
                <option value=""><?= e(content('projects_filter_statuses')) ?></option>
                <?php foreach ($statuses as $s): ?>
                <option value="<?= e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(str_replace('_', ' ', ucwords($s, '_'))) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="select" name="technology" aria-label="Filter by technology">
                <option value=""><?= e(content('projects_filter_technologies')) ?></option>
                <?php foreach ($all_technologies as $t): ?>
                <option value="<?= (int) $t['id'] ?>" <?= (int) $tech_id === (int) $t['id'] ? 'selected' : '' ?>><?= e($t['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn--primary btn--sm" type="submit"><?= e(content('projects_filter')) ?></button>
            <?php if ($search !== '' || $type !== '' || $status !== '' || $tech_id > 0): ?>
            <a class="btn btn--ghost btn--sm" href="<?= e(url('/projects')) ?>"><?= e(content('projects_clear')) ?></a>
            <?php endif; ?>
        </form>

        <?php if (!$projects): ?>
            <p class="muted text-center mt-4"><?= $search !== '' ? content('projects_empty_search') : content('projects_empty') ?></p>
        <?php endif; ?>

        <div class="grid grid--3">
            <?php foreach ($projects as $project): ?>
            <article class="card reveal">
                <?php if ($project['cover_image']): ?>
                <a class="card-media" href="<?= e(url('/projects/' . $project['slug'])) ?>" aria-label="<?= e($project['title']) ?>">
                    <img src="<?= e(upload_url($project['cover_image'])) ?>" alt="<?= e($project['title']) ?>" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="card-body">
                    <div class="tags">
                        <span class="badge-pill badge-pill--<?= $project['status'] === 'completed' ? 'green' : ($project['status'] === 'in_progress' ? 'amber' : 'blue') ?>"><?= e(str_replace('_', ' ', $project['status'])) ?></span>
                        <span class="tag tag--muted"><?= e(str_replace('_', ' ', ucwords($project['project_type'], '_'))) ?></span>
                    </div>
                    <h3><a href="<?= e(url('/projects/' . $project['slug'])) ?>"><?= e($project['title']) ?></a></h3>
                    <?php if ($project['short_description']): ?><p class="card-text"><?= e(truncate($project['short_description'], 120)) ?></p><?php endif; ?>
                    <div class="card-foot tags">
                        <?php foreach ($technologies->forProject((int) $project['id']) as $tech): ?>
                        <span class="tag"><?= e($tech['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-foot">
                        <a class="btn btn--outline-accent btn--sm" href="<?= e(url('/projects/' . $project['slug'])) ?>"><?= e(content('projects_view_case')) ?></a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
        <?php $pqs = qs_params(['q' => $search, 'type' => $type, 'status' => $status, 'technology' => $tech_id]); ?>
        <nav class="pagination" aria-label="Pagination">
            <?php if ($pagination['page'] > 1): ?>
            <a href="<?= e(url('/projects?page=' . ($pagination['page'] - 1) . $pqs)) ?>" aria-label="Previous page">←</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <?php if ($i === (int) $pagination['page']): ?>
                <span class="current" aria-current="page"><?= $i ?></span>
                <?php else: ?>
                <a href="<?= e(url('/projects?page=' . $i . $pqs)) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
            <a href="<?= e(url('/projects?page=' . ($pagination['page'] + 1) . $pqs)) ?>" aria-label="Next page">→</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
    </div>
</section>