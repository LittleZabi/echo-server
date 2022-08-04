from server import saveChildNames


class Renamer:
    def __init__(self, childs) -> None:
        self.childList = childs
        self.dataFile = 'rename.csv'
        self.folderID = '1P8ZR0bsQjSy_978FNKfPeKAl_er4wp0Q'
        self.renamerList = []
        i = self.__write__()
        if i:
            self.__start__()
        else:
            print('[RENAMER] Error on Writing file')

    def __start__(self):
        print('starting...')
        import sys
        sys.path.insert(1, './renamer')
        import finalReady
        q = saveChildNames()
        if q == 'success':
            self.__flush__()
            print('Saved successfully!')

    def __flush__(self):
        with open(self.dataFile, 'r') as file:
            file.write('')
        with open('finalLink.csv', 'r') as file:
            file.write('')

    def __write__(self):
        items = self.childList
        if len(items) > 0:
            with open(self.dataFile, 'w', encoding='utf-8') as file:
                for child in items:
                    try:
                        final = child['finalLink']
                        name = child['new_filename']
                        child_id = child['child_id']
                        final = self.__driveToken__(final)
                        if final != False:
                            file.write(
                                f"{final},{name},{self.folderID},{child_id}\n")
                        else:
                            print(
                                f"{child['finalLink']} is not GDrive Link...")
                    except Exception as e:
                        print(f'[RENAMER -> __write__] {e}')
                return True
        return False

    def __driveToken__(self, link):
        if 'drive.google' in link:
            k = link.split('/file/d/')[1]
            k = k.split('/view')[0]
            return k
        else:
            return False

# if __name__ == '__main__':
#     k = Renamer()
    # link = 'https://drive.google.com/file/d/1-23432l2lk3j4lk234/view?ups=sharing'
