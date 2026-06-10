const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

class ImageOptimizer {
  constructor(config = {}) {
    this.config = {
      inputDir: './',
      recursive: true,
      maxWidth: 1200,
      createAvif: true,
      createWebp: true,
      avifQuality: 50,
      webpQuality: 70,
      jpegQuality: 70,
      pngCompressionLevel: 9,
      ...config,
    };

    this.stats = {
      processed: 0,
      totalOriginalSize: 0,
      totalOptimizedSize: 0,
      totalModernSize: 0,
    };
  }

  getImageFiles(dir, list = []) {
    if (!fs.existsSync(dir)) return list;

    const entries = fs.readdirSync(dir);

    for (const file of entries) {
      const fullPath = path.join(dir, file);
      const stat = fs.statSync(fullPath);

      if (stat.isDirectory() && this.config.recursive) {
        this.getImageFiles(fullPath, list);
      } else if (/\.(png|jpe?g)$/i.test(file)) {
        list.push(fullPath);
      }
    }

    return list;
  }

  async createModernFormats(sharpImg, filePath) {
    const dir = path.dirname(filePath);
    const name = path.basename(filePath, path.extname(filePath));
    const results = [];

    if (this.config.createWebp) {
      const outPath = path.join(dir, `${name}.webp`);
      await sharpImg
        .clone()
        .webp({
          quality: this.config.webpQuality,
          effort: 6,
        })
        .toFile(outPath);

      const size = fs.statSync(outPath).size;
      results.push({ format: 'WebP', size });
      this.stats.totalModernSize += size;
    }

    if (this.config.createAvif) {
      const outPath = path.join(dir, `${name}.avif`);
      await sharpImg
        .clone()
        .avif({
          quality: this.config.avifQuality,
          effort: 6,
        })
        .toFile(outPath);

      const size = fs.statSync(outPath).size;
      results.push({ format: 'AVIF', size });
      this.stats.totalModernSize += size;
    }

    return results;
  }

  async optimizeOriginal(filePath, img, ext) {
    const temp = `${filePath}.tmp`;

    if (ext === '.jpg' || ext === '.jpeg') {
      await img
        .jpeg({
          quality: this.config.jpegQuality,
          mozjpeg: true,
          progressive: true,
        })
        .toFile(temp);
    } else if (ext === '.png') {
      await img
        .png({
          compressionLevel: this.config.pngCompressionLevel,
          palette: true,
        })
        .toFile(temp);
    } else {
      return null;
    }

    return temp;
  }

  formatBytes(bytes) {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
  }

  async optimize(filePath) {
    const originalSize = fs.statSync(filePath).size;
    const ext = path.extname(filePath).toLowerCase();

    let img = sharp(filePath);
    const meta = await img.metadata();

    if (meta.width && meta.width > this.config.maxWidth) {
      img = img.resize({
        width: this.config.maxWidth,
        fit: 'inside',
        withoutEnlargement: true,
      });
    }

    const modern = await this.createModernFormats(img, filePath);

    const temp = await this.optimizeOriginal(filePath, img, ext);

    let optimizedSize = originalSize;

    if (temp) {
      const newSize = fs.statSync(temp).size;

      if (newSize < originalSize) {
        fs.renameSync(temp, filePath);
        optimizedSize = newSize;
      } else {
        fs.unlinkSync(temp);
      }
    }

    this.stats.processed++;
    this.stats.totalOriginalSize += originalSize;
    this.stats.totalOptimizedSize += optimizedSize;

    return {
      file: path.basename(filePath),
      originalSize,
      optimizedSize,
      saved: originalSize - optimizedSize,
      modern,
    };
  }

  printResult(result) {
    const savedPercent = result.originalSize > 0 ? ((result.saved / result.originalSize) * 100).toFixed(1) : 0;

    console.log(`✓ ${result.file}`);

    if (result.saved > 0) {
      console.log(`  Original: ${this.formatBytes(result.originalSize)} → ${this.formatBytes(result.optimizedSize)} (saved ${savedPercent}%)`);
    } else {
      console.log(`  Size: ${this.formatBytes(result.optimizedSize)} (already optimized)`);
    }

    result.modern.forEach((f) => {
      console.log(`  + ${f.format}: ${this.formatBytes(f.size)}`);
    });

    console.log();
  }

  printStats() {
    const totalSaved = this.stats.totalOriginalSize - this.stats.totalOptimizedSize;
    const savedPercent = this.stats.totalOriginalSize > 0 ? ((totalSaved / this.stats.totalOriginalSize) * 100).toFixed(1) : 0;

    console.log('═'.repeat(60));
    console.log('📊 OPTIMIZATION SUMMARY');
    console.log('═'.repeat(60));
    console.log(`Files processed: ${this.stats.processed}`);
    console.log(`Original total: ${this.formatBytes(this.stats.totalOriginalSize)}`);
    console.log(`Optimized total: ${this.formatBytes(this.stats.totalOptimizedSize)}`);
    console.log(`Modern formats: ${this.formatBytes(this.stats.totalModernSize)}`);
    console.log(`\nTotal saved: ${this.formatBytes(totalSaved)} (${savedPercent}%)`);
    console.log('═'.repeat(60));
  }

  async run() {
    const files = this.getImageFiles(this.config.inputDir);

    if (files.length === 0) {
      console.log('⚠️  No images found');
      return;
    }

    console.log('🚀 Image Optimizer');
    console.log(`📁 Directory: ${this.config.inputDir}`);
    console.log(`🖼️  Found ${files.length} image(s)\n`);

    for (const file of files) {
      try {
        const result = await this.optimize(file);
        this.printResult(result);
      } catch (error) {
        console.log(`✗ ${path.basename(file)} - Error: ${error.message}\n`);
      }
    }

    if (this.stats.processed > 0) {
      console.log();
      this.printStats();
    }
  }
}

module.exports = ImageOptimizer;

new ImageOptimizer().run().catch(console.error);
