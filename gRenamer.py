import os
from gDriveFile import createService


class doRename:
    def __init__(self):
        # self.root = f"{os.getcwd()}\\"
        self.root = f"."
        self.CLIENT_SECRET_FILE = self.root + '\\credentials.json'
        self.API_NAME = 'drive'
        self.API_VERSION = 'v3'
        self.fileToCopyId = '13UVwqTY0LDygku8-fA0L5Qxt-gyhRihE'
        self.copyFolderId = '1cSRwzCcLUgCZm4b0yBLyyi6HxWnzdQL4'
        self.renamerCsvFile = self.root + '\\rename.csv'
        self.finalLinks = self.root + '\\finalLinks.csv'
        self.allLinkData = []
        self.SCOPES = ['https://www.googleapis.com/auth/drive.metadata.readonly',
                       'https://www.googleapis.com/auth/drive']
        self.service = createService(
            self.CLIENT_SECRET_FILE, self.API_NAME, self.API_VERSION, self.SCOPES)
        self.readRenamerFile()
        self.doStuff()

    def doStuff(self):
        for linkData in self.allLinkData:
            try:
                temp_data = linkData.strip().split(",")
                fileLink_ = temp_data[0]
                fileName_ = temp_data[1]
                fileFolderId_ = temp_data[2]
                child_id = temp_data[3]
                print("DataRead:", fileLink_, fileName_, fileFolderId_)
                file_to_copy_id = fileLink_
                copy_file_name = fileName_
                copy_folder_id = fileFolderId_
                self.copied_file = {'title': 'COPYEDFILE',
                                    'name': copy_file_name, 'parents': [copy_folder_id]}
                copy_file_to_drive_status, temp_id = self.copyFileToDrive(
                    file_to_copy_id=file_to_copy_id, folder_id=copy_folder_id, copied_file_body=self.copied_file)
                print('dirve state: ', copy_file_to_drive_status)
                if copy_file_to_drive_status:
                    get_Publink_status, temp_link = self.getPublicLink(temp_id)
                    if get_Publink_status:
                        try:
                            with open(self.finalLinks, "a") as file:
                                print('file writing...', temp_link, child_id)
                                file.write(f"{temp_link}, {child_id}\n")
                                print("Final Link Found::",
                                      temp_link, fileName_)
                        except Exception as e:
                            self.__ErrFire__(
                                module='gRenamer', function='doStuff (copy_file_to_drive_status) ' + copy_file_to_drive_status, class_='doRenamer', err=e)
            except Exception as e:
                self.__ErrFire__(module='gRenamer',
                                 class_='doRename', function='doStuff', err=e)
                continue

    def __del__(self):
        print('Distruction of the renamer object (:')

    def __ErrFire__(self, module='', class_='', function='', err='', line=''):
        y = '['
        if module != '':
            y += module
        if class_ != '':
            y += '.'+class_
        y += ' => '
        if function != '':
            y += function
        if line != '':
            y += ': ' + line
        y += '] '
        if err != '':
            print(y, err)
            return y, err
        print(y)
        return y

    def readRenamerFile(self):
        try:
            with open(self.renamerCsvFile, 'r') as file:
                self.allLinkData = file.readlines()
        except Exception as e:
            self.__ErrFire__(module='gRenamer', function='readRenamerFile',
                             class_='doRename', err=e)

    def copyFileToDrive(self, file_to_copy_id='', folder_id='', copied_file_body=''):
        try:
            copied_file_obj = self.service.files().copy(
                fileId=file_to_copy_id, body=self.copied_file).execute()
            copied_file_file_id = copied_file_obj['id']
            print("infun1", copied_file_file_id)
            return True, copied_file_file_id
        except Exception as e:
            self.__ErrFire__(
                module='gRenamer', function='copyFileToDrive', class_='doRenamer', err=e)

    def getPublicLink(self, file_id=''):
        request_body = {
            'role': 'reader',
            'type': 'anyone'
        }
        response_permission = service.permissions().create(
            fileId=file_id,
            body=request_body
        ).execute()
        response_share_link = service.files().get(
            fileId=file_id,
            fields='webViewLink'
        ).execute()
        print("InFunSction:get_Publink(_)::", response_share_link)
        return True, response_share_link['webViewLink']
