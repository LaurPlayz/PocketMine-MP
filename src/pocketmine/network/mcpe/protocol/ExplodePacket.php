<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\SessionHandler;
use pocketmine\network\mcpe\NetworkBinaryStream;

class ExplodePacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::EXPLODE_PACKET;

	/** @var Vector3 */
	public $position;
	/** @var float */
	public $radius;
	/** @var Vector3[] */
	public $records = [];

	protected function decodePayload(NetworkBinaryStream $in) : void{
		$this->position = $in->getVector3();
		$this->radius = (float) ($in->getVarInt() / 32);
		$count = $in->getUnsignedVarInt();
		for($i = 0; $i < $count; ++$i){
			$x = $y = $z = null;
			$in->getSignedBlockPosition($x, $y, $z);
			$this->records[$i] = new Vector3($x, $y, $z);
		}
	}

	protected function encodePayload(NetworkBinaryStream $out) : void{
		$out->putVector3($this->position);
		$out->putVarInt((int) ($this->radius * 32));
		$out->putUnsignedVarInt(count($this->records));
		if(count($this->records) > 0){
			foreach($this->records as $record){
				$out->putSignedBlockPosition((int) $record->x, (int) $record->y, (int) $record->z);
			}
		}
	}

	public function handle(SessionHandler $handler) : bool{
		return $handler->handleExplode($this);
	}
}
