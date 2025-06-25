<script setup>
import { shallowRef, reactive } from 'vue';
import { MapPin, Phone, Mail, Clock } from 'lucide-vue-next'; // Pastikan menggunakan lucide-vue-next
import { onMounted, onUnmounted, ref, computed } from 'vue';


const wasDarkMode = ref(false);

onMounted(() => {
    const htmlElement = document.documentElement;
    // Cek apakah dark mode aktif saat komponen dimuat
    if (htmlElement.classList.contains('dark')) {
        wasDarkMode.value = true;
        // Hapus class 'dark' untuk memaksa light mode
        htmlElement.classList.remove('dark');
    }
});

onUnmounted(() => {
    // Saat meninggalkan halaman, kembalikan class 'dark' jika sebelumnya aktif
    if (wasDarkMode.value) {
        document.documentElement.classList.add('dark');
    }
});

const contactInfo = shallowRef([
  {
    icon: MapPin,
    title: "Alamat Kantor",
    details: ["Jl. DR. Ide Anak Agung Gde Agung", "Kota Jakarta Selatan, DKI Jakarta", "Indonesia"]
  },
  {
    icon: Phone,
    title: "Telepon",
    details: ["+62 858-8628-3658", "+62 858-1049-2011", "WhatsApp Available"]
  },
  {
    icon: Mail,
    title: "Email",
    details: ["intergeo.mitigasi@gmail.com"]
  },
  {
    icon: Clock,
    title: "Jam Operasional",
    details: ["Senin - Jumat: 08:00 - 17:00", "Sabtu: 08:00 - 14:00", "Minggu: Tutup"]
  }
]);

const form = reactive({
  fullName: '',
  email: '',
  phoneNumber: '',
  serviceType: '',
  projectDescription: '',
  agreement: false,
});

const submitForm = () => {
  if (!form.agreement) {
    alert('Anda harus menyetujui syarat dan ketentuan.');
    return;
  }
  // Logika untuk mengirim form, misalnya menggunakan fetch atau axios
  console.log('Form Submitted:', form);
  alert('Permintaan konsultasi survey Anda telah terkirim!');
  // Reset form (opsional)
  // form.fullName = '';
  // form.email = '';
  // form.phoneNumber = '';
  // form.serviceType = '';
  // form.projectDescription = '';
  // form.agreement = false;
};

// Komponen Button, Input, Textarea, Card, CardContent, CardHeader, CardTitle
// dari '@/components/ui/*' perlu di-handle secara terpisah.
// Untuk contoh ini, saya mereplikasi strukturnya menggunakan elemen HTML standar
// dan styling Tailwind. Jika Anda menggunakan library komponen UI Vue atau
// memiliki komponen kustom Vue sendiri (mirip shadcn/ui untuk Vue),
// Anda bisa mengimpor dan menggunakannya.
</script>


<template>
  <section id="contact" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
          Siap Memulai <span class="text-blue-600">Survey Geofisika</span> Anda?
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Hubungi kami sekarang untuk konsultasi survey seismik, GPR, dan georadar gratis. Tim ahli survey geofisika kami siap membantu
          proyek survey Anda dengan teknologi terdepan dan solusi terbaik.
        </p>
      </div>

      <div class="grid lg:grid-cols-3 gap-12">
        <div class="lg:col-span-1 space-y-6">
          <div
              v-for="(info, index) in contactInfo"
              :key="index"
              class="border-0 shadow-md hover:shadow-lg transition-shadow rounded-lg overflow-hidden bg-white"
          >
            <div class="p-4">
              <div class="flex items-center space-x-3 text-lg mb-2">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <component :is="info.icon" class="w-5 h-5 text-blue-600" />
                </div>
                <span>{{ info.title }}</span>
              </div>
              <div>
                <p
                    v-for="(detail, idx) in info.details"
                    :key="idx"
                    class="text-gray-600 text-sm leading-relaxed"
                >
                  {{ detail }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-2">
          <div class="border-0 shadow-xl rounded-lg overflow-hidden bg-white">
            <div class="p-6 sm:p-8">
              <h3 class="text-2xl text-gray-900 font-semibold mb-2">
                Dapatkan Konsultasi Survey Geofisika Gratis
              </h3>
              <p class="text-gray-600 mb-6">
                Isi formulir di bawah ini dan tim ahli survey geofisika kami akan menghubungi Anda dalam 24 jam untuk membahas kebutuhan survey seismik, GPR, atau georadar Anda.
              </p>
              <form @submit.prevent="submitForm" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label for="fullName" class="block text-sm font-medium text-gray-700 mb-2">
                      Nama Lengkap *
                    </label>
                    <input
                        id="fullName"
                        v-model="form.fullName"
                        type="text"
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    />
                  </div>
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                      Email *
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="nama@email.com"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    />
                  </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700 mb-2">
                      Nomor Telepon *
                    </label>
                    <input
                        id="phoneNumber"
                        v-model="form.phoneNumber"
                        type="tel"
                        placeholder="+62 858-8628-3658"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    />
                  </div>
                  <div>
                    <label for="serviceType" class="block text-sm font-medium text-gray-700 mb-2">
                      Jenis Layanan Survey Geofisika
                    </label>
                    <select
                        id="serviceType"
                        v-model="form.serviceType"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                    >
                      <option value="">Pilih layanan survey geofisika</option>
                      <option>Survey Seismik</option>
                      <option>Ground Penetrating Radar (GPR)</option>
                      <option>Survey Georadar</option>
                      <option>Survey dan Pemetaan</option>
                      <option>Studi Kelayakan Tambang</option>
                      <option>Survey Infrastruktur & Jembatan</option>
                      <option>Analisis Geoteknik</option>
                      <option>Interpretasi Data Survey</option>
                      <option>Konsultasi Survey Geofisika</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label for="projectDescription" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Proyek Survey Geofisika *
                  </label>
                  <textarea
                      id="projectDescription"
                      v-model="form.projectDescription"
                      placeholder="Ceritakan detail proyek survey geofisika Anda, lokasi, tujuan survey (deteksi utilitas, analisis struktur tanah, eksplorasi mineral, dll), dan timeline yang diinginkan..."
                      class="w-full h-32 resize-none px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      required
                  ></textarea>
                </div>

                <div class="flex items-center space-x-2">
                  <input
                      id="agreement"
                      v-model="form.agreement"
                      type="checkbox"
                      class="rounded h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                      required
                  />
                  <label for="agreement" class="text-sm text-gray-600">
                    Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> yang berlaku
                  </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 text-lg font-semibold rounded-md transition-colors"
                >
                  Kirim Permintaan Konsultasi Survey Geofisika
                </button>

                <p class="text-center text-sm text-gray-500">
                  Tim ahli survey geofisika kami akan merespons dalam waktu maksimal 24 jam
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-20 text-center bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-12 text-white">
        <h3 class="text-3xl font-bold mb-4">
          Survey Geofisika Darurat? Hubungi Kami Sekarang!
        </h3>
        <p class="text-xl mb-8 opacity-90">
          Layanan 24/7 untuk kebutuhan survey seismik, GPR, dan georadar mendesak
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
          <a
              href="tel:+6285886283658"
              class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
          >
            📞 +62 858-8628-3658
          </a>
          <a
              href="https://wa.me/6285886283658"
              target="_blank"
              rel="noopener noreferrer"
              class="bg-green-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-600 transition-colors"
          >
            💬 WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>
</template>



<style scoped>
/* Gaya spesifik komponen bisa ditambahkan di sini jika perlu */
/* Pastikan elemen select dan checkbox tampil dengan baik dengan Tailwind */
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007AFF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
  background-repeat: no-repeat;
  background-position: right .7em top 50%, 0 0;
  background-size: .65em auto, 100%;
  padding-right: 2.5em; /* ensure space for arrow */
}
</style>