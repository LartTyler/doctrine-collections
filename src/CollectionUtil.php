<?php
	namespace DaybreakStudios\DoctrineCollections;

	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria;
	use Doctrine\Common\Collections\Selectable;

	/**
	 * Class CollectionUtil
	 *
	 * @package DaybreakStudios\Utility\DoctrineCollections
	 */
	final class CollectionUtil {
		/**
		 * @param Collection    $collection
		 * @param Criteria|null $criteria
		 *
		 * @return Collection
		 */
		public static function getOrFilter(Collection $collection, Criteria $criteria = null) {
			if (!$criteria || !($collection instanceof Selectable))
				return $collection;

			return $collection->matching($criteria);
		}

		/**
		 * @param Selectable $collection
		 * @param array      $params
		 *
		 * @return mixed|null
		 */
		public static function getSingleItem(Selectable $collection, array $params) {
			$criteria = Criteria::create()
				->setMaxResults(1);

			foreach ($params as $key => $value)
				$criteria->andWhere(Criteria::expr()->eq($key, $value));

			return self::getSingleItemByCriteria($collection, $criteria);
		}

		/**
		 * @param Selectable $collection
		 * @param Criteria   $criteria
		 *
		 * @return mixed|null
		 */
		public static function getSingleItemByCriteria(Selectable $collection, Criteria $criteria) {
			$matching = $collection->matching($criteria);

			if (!$matching->count())
				return null;

			return $matching->first();
		}

		/**
		 * @param mixed $item
		 *
		 * @return bool
		 */
		public static function isIterable($item) {
			return is_array($item) || $item instanceof \Traversable;
		}

		/**
		 * Adds all elements contained in $items to a collection.
		 *
		 * @param Collection         $collection
		 * @param array|\Traversable $items
		 *
		 * @return Collection
		 */
		public static function addAll(Collection $collection, $items) {
			if (!self::isIterable($items))
				throw new \InvalidArgumentException('$items must be an array, or implement \\Taversable');

			foreach ($items as $item)
				$collection->add($item);

			return $collection;
		}
	}