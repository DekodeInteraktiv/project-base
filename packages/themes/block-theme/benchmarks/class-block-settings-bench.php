<?php
/**
 * Block settings benchmarks.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );

namespace BlockTheme\Benchmarks;

use PhpBench\Attributes\ParamProviders;

use function BlockTheme\BlockSettings\do_override_block_type_args;

require_once dirname( __DIR__ ) . '/includes/block-settings.php';

/**
 * Benchmarks the register_block_type_args filter callback, which runs once per registered block type on every request.
 */
class Block_Settings_Bench {

	/**
	 * Block types as WordPress passes them to the filter: one the callback changes, one it passes through.
	 *
	 * @return \Generator
	 */
	public function provide_block_types(): \Generator {
		$args = [
			'api_version' => 3,
			'attributes'  => [
				'align' => [ 'type' => 'string' ],
				'lock'  => [ 'type' => 'object' ],
			],
			'supports'    => [ 'anchor' => true ],
		];

		yield 'core/media-text' => [
			'args' => $args,
			'name' => 'core/media-text',
		];
		yield 'core/paragraph' => [
			'args' => $args,
			'name' => 'core/paragraph',
		];
	}

	/**
	 * Time do_override_block_type_args() for each block type.
	 *
	 * @param array $params Block args and name.
	 * @return void
	 */
	#[ParamProviders( [ 'provide_block_types' ] )]
	public function bench_override_block_type_args( array $params ): void {
		do_override_block_type_args( $params['args'], $params['name'] );
	}
}
