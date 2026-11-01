const schoolSelect       = document.querySelector('#school_id');
const distributionSelect = document.querySelector('#distribution_id');

if (schoolSelect && distributionSelect) {
    schoolSelect.addEventListener('change', loadDistributions);

    if (schoolSelect.value) {
        loadDistributions();
    }
}

async function loadDistributions() {
    const schoolId = schoolSelect.value;

    if (! schoolId) {
        distributionSelect.innerHTML = '<option value="">Pilih sekolah terlebih dahulu</option>';
        return;
    }

    distributionSelect.innerHTML = '<option value="">Memuat distribusi...</option>';
    distributionSelect.disabled  = true;

    try {
        const response = await fetch(`/schools/${schoolId}/distributions`, {
            headers: { 'Accept': 'application/json' },
        });

        if (! response.ok) throw new Error('Gagal memuat distribusi.');

        const items = await response.json();

        distributionSelect.innerHTML = '<option value="">Pilih distribusi</option>';

        if (items.length === 0) {
            distributionSelect.innerHTML = '<option value="">Tidak ada distribusi untuk sekolah ini</option>';
            return;
        }

        const selected = distributionSelect.dataset.selected ?? '';

        items.forEach((item) => {
            const option    = new Option(
                `${item.distribution_date} - ${item.menu_description}`,
                item.id,
                false,
                String(item.id) === String(selected)
            );
            distributionSelect.add(option);
        });
    } catch (error) {
        distributionSelect.innerHTML = '<option value="">Gagal memuat distribusi</option>';
    } finally {
        distributionSelect.disabled = false;
    }
}