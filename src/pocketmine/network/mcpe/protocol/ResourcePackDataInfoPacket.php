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


use pocketmine\network\mcpe\handler\SessionHandler;
use pocketmine\network\mcpe\NetworkBinaryStream;

class ResourcePackDataInfoPacket extends BasePacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_DATA_INFO_PACKET;

	/** @var string */
	public $packId;
	/** @var int */
	public $maxChunkSize;
	/** @var int */
	public $chunkCount;
	/** @var int */
	public $compressedPackSize;
	/** @var string */
	public $sha256;

	protected function decodePayload(NetworkBinaryStream $in) : void{
		$this->packId = $in->getString();
		$this->maxChunkSize = $in->getLInt();
		$this->chunkCount = $in->getLInt();
		$this->compressedPackSize = $in->getLLong();
		$this->sha256 = $in->getString();
	}

	protected function encodePayload(NetworkBinaryStream $out) : void{
		$out->putString($this->packId);
		$out->putLInt($this->maxChunkSize);
		$out->putLInt($this->chunkCount);
		$out->putLLong($this->compressedPackSize);
		$out->putString($this->sha256);
	}

	public function handle(SessionHandler $handler) : bool{
		return $handler->handleResourcePackDataInfo($this);
	}
}
