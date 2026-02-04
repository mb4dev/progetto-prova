<?php

namespace features\resources;

enum ResourceType: string {
	case FIELD = 'campo';
	case COURSE = 'corso';
	case SUBSCRIPTION = 'abbonamento';
}
