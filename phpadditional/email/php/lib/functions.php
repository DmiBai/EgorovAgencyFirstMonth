<?php

function timestampToStr($timestamp)
{
    $timestamp = str_replace(' ', '_', $timestamp);
    $timestamp = str_replace('-', '', $timestamp);
    $timestamp = str_replace(':', '', $timestamp);
    return $timestamp;
}