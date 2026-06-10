import fs from 'fs';
import archiver from 'archiver';
import path from 'path';

function zip() {
  const isAlreadyExists = fs.existsSync('theme.zip');
  if (isAlreadyExists) {
    fs.unlinkSync('theme.zip');
  }
  const output = fs.createWriteStream('theme.zip');
  const archive = archiver('zip', { zlib: { level: 9 } });
  const excludeDirs = ['node_modules', 'vendor', '.git'];
  output.on('close', () => {
    console.log(`✅ Arhive created: ${archive.pointer()} bytes.`);
  });
  archive.on('error', (err) => {
    throw err;
  });
  archive.pipe(output);
  function addFolderToArchive(folder) {
    const items = fs.readdirSync(folder);
    for (const item of items) {
      const fullPath = path.join(folder, item);
      const relPath = path.relative(process.cwd(), fullPath);
      const stats = fs.statSync(fullPath);
      if (stats.isDirectory()) {
        if (!excludeDirs.includes(item)) {
          addFolderToArchive(fullPath);
        }
      } else {
        archive.file(fullPath, { name: relPath });
      }
    }
  }
  addFolderToArchive(process.cwd());
  archive.finalize();
}

zip();
