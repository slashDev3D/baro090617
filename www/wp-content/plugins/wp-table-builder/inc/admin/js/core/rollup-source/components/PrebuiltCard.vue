<template>
	<div class="wptb-prebuilt-card" @click="setCardActive" :class="{ 'wptb-prebuilt-card-active': isActive }">
		<div class="wptb-prebuilt-card-preview">
			<div
				ref="tablePreview"
				v-show="!liveDisplayEnabled || !isActive"
				class="wptb-prebuilt-table-wrapper wptb-unselectable"
				v-html="table"
			></div>
			<div v-if="isActive" class="wptb-prebuilt-card-controls">
				<prebuilt-card-control
					:disabled="controlDisabled"
					orientation="row"
					v-model="columns"
					:min="min.cols"
					:max="max.cols"
					:step="controlStep.col"
				></prebuilt-card-control>
				<prebuilt-card-control
					:disabled="controlDisabled"
					orientation="col"
					v-model="rows"
					:min="min.rows"
					:max="max.rows"
					:step="controlStep.row"
				></prebuilt-card-control>
			</div>
			<prebuilt-live-display
				v-if="isActive && liveDisplayEnabled"
				:rows="rows"
				:cols="columns"
				:table="previewTableElement"
				:selected-cells="selectedCells"
				:enable-new-cell-indicator="id !== 'blank'"
			></prebuilt-live-display>
			<div
				v-if="!isActive"
				class="wptb-prebuilt-card-icon wptb-prebuilt-card-fav-icon wptb-plugin-filter-box-shadow-md-close"
				:class="{ 'is-fav': fav }"
				v-html="favIcon"
				@click.capture.prevent.stop="favAction"
			></div>
			<prebuilt-card-delete-module
				v-if="isActive && deleteIcon !== ''"
				:delete-icon="deleteIcon"
				:message="strings.deleteConfirmation"
				:yes-icon="appData.icons.checkIcon"
				:no-icon="appData.icons.crossIcon"
				@confirm="deleteAction"
			></prebuilt-card-delete-module>
		</div>
		<div class="wptb-prebuilt-card-footer">
			<div class="wptb-prebuilt-card-footer-element" v-if="!isActive" v-html="transformedName"></div>
			<div
				class="wptb-prebuilt-card-footer-element wptb-prebuilt-card-footer-button-holder"
				:class="{ 'wptb-prebuilt-card-footer-button-holder-single': !editEnabled }"
				v-else
			>
				<div
					class="wptb-prebuilt-footer-button wptb-prebuilt-footer-generate wptb-unselectable"
					@click.prevent="cardGenerate"
				>
					{{ strings.generate | cap }}
				</div>
				<div
					v-if="editEnabled"
					class="wptb-prebuilt-footer-button wptb-prebuilt-footer-edit wptb-unselectable"
					@click.prevent="cardEdit"
				>
					{{ strings.edit | cap }}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import PrebuiltCardControl from './PrebuiltCardControl';
import PrebuiltLiveDisplay from './PrebuiltLiveDisplay';
import PrebuiltCardDeleteModule from './PrebuiltCardDeleteModule';

export default {
	props: {
		name: {
			required: true,
		},
		id: {
			type: String,
			required: true,
		},
		table: {
			type: String,
			default: '<p class="wptb-prebuilt-blank">+</p>',
		},
		isActive: {
			type: Boolean,
			default: false,
		},
		disabled: {
			default: false,
		},
		liveDisplayEnabled: {
			type: Boolean,
			default: true,
		},
		searchString: {
			type: String,
			default: '',
		},
		fav: {
			type: Boolean,
			default: false,
		},
		favIcon: {
			type: String,
			default: '',
		},
		deleteIcon: {
			type: String,
			default: '',
		},
	},
	components: { PrebuiltCardControl, PrebuiltLiveDisplay, PrebuiltCardDeleteModule },
	data() {
		return {
			rows: 1,
			columns: 1,
			initial: {
				rows: 1,
				columns: 1,
			},
			min: {
				rows: 1,
				cols: 1,
			},
			max: {
				rows: 30,
				cols: 30,
			},
			selectedCells: {
				rowOperation: [],
				colOperation: [],
			},
			controlStep: {
				row: 1,
				col: 1,
			},
		};
	},
	watch: {
		selectedCells: {
			handler() {
				this.controlStep.row = Math.max(
					Math.max(
						this.selectedCells.colOperation.length > 0 ? this.initial.rows : 1,
						this.selectedCells.rowOperation.length / this.initial.columns
					),
					1
				);

				this.controlStep.col = Math.max(
					Math.max(
						this.selectedCells.rowOperation.length > 0 ? this.initial.columns : 1,
						this.selectedCells.colOperation.length / this.initial.rows
					),
					1
				);

				if (this.selectedCells.rowOperation.length === 0 && this.selectedCells.colOperation.length === 0) {
					if (this.id !== 'blank') {
						this.rows = this.initial.rows;
						this.columns = this.initial.columns;
					}
				}
			},
			deep: true,
		},
	},
	computed: {
		transformedName() {
			if (this.searchString !== '') {
				const regexp = new RegExp(`(${this.searchString})`, 'ig');
				const transform = this.name.replace(
					regexp,
					'<span class="wptb-prebuilt-card-search-indicator">$&</span>'
				);
				return `<span class="wptb-prebuilt-card-search-indicator-main">${transform}</span>`;
			}

			return this.name;
		},
		editEnabled() {
			if (this.isDevBuild()) {
				return (
					this.id !== 'blank' &&
					(this.id.startsWith(this.appData.teamTablePrefix) ||
						!this.id.startsWith(this.appData.teamTablePrefix))
				);
			}

			return this.id !== 'blank' && !this.id.startsWith(this.appData.teamTablePrefix);
		},
		previewTableElement() {
			let table = this.$refs.tablePreview.querySelector('table');

			// if no table is found, send a table with one row and and one cell in it as a default
			if (!table) {
				const range = document.createRange();
				range.setStart(document.body, 0);
				[table] = range.createContextualFragment('<table><tr><td></td></tr></table>').childNodes;
			}

			return table;
		},
		controlDisabled() {
			if (this.id === 'blank') {
				return false;
			}
			return this.selectedCells.colOperation.length === 0 && this.selectedCells.rowOperation.length === 0;
		},
	},
	mounted() {
		this.$nextTick(() => {
			this.initial.rows = this.rows;

			const { tablePreview } = this.$refs;
			// eslint-disable-next-line no-unused-vars
			const { width: wrapperWidth, height: wrapperHeight } = tablePreview.getBoundingClientRect();

			const prebuilt = tablePreview.querySelector('table');

			if (prebuilt) {
				const maxWidth = prebuilt.dataset.wptbTableContainerMaxWidth;
				if (maxWidth) {
					prebuilt.style.width = `${maxWidth}px`;
				} else {
					prebuilt.style.width = `${700}px`;
				}

				const padding = 40;
				const { width: prebuiltWidth, height: prebuiltHeight } = prebuilt.getBoundingClientRect();
				const widthScale = wrapperWidth / (prebuiltWidth + padding);
				const heightScale = 125 / (prebuiltHeight + padding);

				prebuilt.style.transform = `scale(${Math.min(widthScale, heightScale)})`;

				// @deprecated
				// seems like google fixed this issue with latest version
				// fix for chrome browsers where table previews are distorted for tables with separated columns and row
				// if (window.navigator.vendor.includes('Google')) {
				// 	const borderCollapseType = prebuilt.style.borderCollapse;
				// 	if (borderCollapseType === 'separate') {
				// 		const borderHorizontalSpacing = parseInt(prebuilt.dataset.borderSpacingColumns, 10);
				// 		const cellCount = parseInt(prebuilt.dataset.wptbCellsWidthAutoCount, 10);
				//
				// 		prebuilt.style.marginLeft = `${(cellCount + 1) * borderHorizontalSpacing * -1}px`;
				// 	}
				// }

				if (this.id !== 'blank') {
					const tableRows = Array.from(prebuilt.querySelectorAll('tr'));
					const totalRows = tableRows.length;
					this.rows = totalRows;
					this.min.rows = totalRows;

					let minCols = 1;

					// eslint-disable-next-line array-callback-return
					tableRows.map((t) => {
						const totalCells = t.querySelectorAll('td').length;
						if (minCols < totalCells) {
							minCols = totalCells;
						}
					});

					this.min.cols = minCols;
					this.columns = minCols;

					this.initial.columns = this.columns;
					this.initial.rows = this.rows;
				}
			}
		});
	},
	methods: {
		setCardActive() {
			if (!this.isActive) {
				this.$emit('cardActive', this.id);
			}
		},
		cardGenerate() {
			if (!this.disabled) {
				const operationCells =
					this.selectedCells.colOperation.length > 0
						? this.selectedCells.colOperation
						: this.selectedCells.rowOperation;

				this.$emit('cardGenerate', this.id, this.columns, this.rows, operationCells);
			}
		},
		cardEdit() {
			if (!this.disabled) {
				this.$emit('cardEdit', this.id);
			}
		},
		favAction() {
			this.$emit('favAction', this.id);
		},
		deleteAction() {
			this.$emit('deleteAction', this.id);
		},
	},
};
</script>
