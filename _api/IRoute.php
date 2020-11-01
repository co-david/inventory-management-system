<?php

interface IRoute
{
	public function __construct(string $method, array $queryArr = []);
	public function run() : array;
}