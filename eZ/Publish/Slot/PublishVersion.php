<?php
/**
 * This file is part of the EzSystemsRecommendationBundle package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace EzSystems\RecommendationBundle\eZ\Publish\Slot;

use eZ\Publish\Core\SignalSlot\Signal;

class PublishVersion extends PersistenceAwareBase
{
    public function receive(Signal $signal)
    {
        if (!$signal instanceof Signal\ContentService\PublishVersionSignal) {
            return;
        }

        $relations = $this->persistenceHandler
            ->contentHandler()
            ->loadReverseRelations($signal->contentId);

        $this->client->updateContent($signal->contentId, $signal->versionNo);

        foreach ($relations as $relation) {
            $this->client->updateContent($relation->destinationContentId);
        }
    }
}
