eocd_sig = b"\x50\x4b\x05\x06"
cd_fh_sig = b"\x50\x4b\x01\x02"
local_fh_sig = b"\x50\x4b\x03\x04"
from struct import pack



def create_zip(name, data):
    local_fh = zip_local_fileheader(len(name), name, len(data), len(data), data)
    cd_fh = zip_cd_fileheader(len(name), name, len(data), len(data), 0)
    eocd = zip_eocd(len(local_fh) , len(cd_fh))
    return local_fh + cd_fh + eocd


def zip_eocd(cd_offset, cd_size, disk_no=0, disk_no_cd=0, disk_entries=1, total_entries=1, comment_len=0, comment=b""):
    return eocd_sig + pack("<HHHHIIH",
            disk_no,
            disk_no_cd,
            disk_entries,
            total_entries,
            cd_size,
            cd_offset,
            comment_len) + comment


def zip_cd(list_file_headers):
    res = "";
    for file_header in list_file_headers:
        res += file_header
    return res


def zip_cd_fileheader(file_name_len,file_name,compressed_size, uncompressed_size, local_header_offset,  version=0x31e, version_needed=0xa, flags=0, compression=0, crc32=0, modtime=0, moddate=0,  extra_field_len=0, file_comment_len=0, disk_start=0, internal_attr=0,external_attr=0, extra_field=b"", file_comment=b""):
    return cd_fh_sig + pack("<HHHHHHIIIHHHHHII",
            version,
            version_needed,
            flags,
            compression,
            modtime,
            moddate,
            crc32,
            compressed_size,
            uncompressed_size,
            file_name_len,
            extra_field_len,
            file_comment_len,
            disk_start,
            internal_attr,
            external_attr,
            local_header_offset) + file_name + extra_field + file_comment

def zip_local_fileheader(file_name_len, file_name, compressed_size, uncompressed_size, data, version=0xa, flags=0, compression=0, modtime=0, moddate=0, crc32=0, extra_field_len=0, extra_field=b""):
        return local_fh_sig + pack("<HHHHHIIIHH",
                version,
                flags,
                compression,
                modtime,
                moddate,
                crc32,
                compressed_size,
                uncompressed_size,
                file_name_len,
                extra_field_len) + file_name + extra_field + data


